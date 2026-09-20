<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Feature\Resources\Invoices\Exports;

use DateTimeImmutable;
use DateTimeZone;
use N1ebieski\KSEFClient\Actions\DecryptDocument\DecryptDocumentAction;
use N1ebieski\KSEFClient\Actions\DecryptDocument\DecryptDocumentHandler;
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Exceptions\HttpClient\BadRequestException;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Env;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\Requests\Testdata\Subject\SubjectType;
use Throwable;
use ZipArchive;

/** @var AbstractTestCase $this */

beforeAll(function (): void {
    $client = new ClientBuilder()
        ->withMode(Mode::Test)
        ->build();

    try {
        $client->testdata()->subject()->create([
            'subjectNip' => Env::string('NIP_2'),
            'subjectType' => SubjectType::EnforcementAuthority,
            'description' => 'Subject who gives InvoiceWrite permission',
        ])->status();
    } catch (BadRequestException $exception) {
        if (str_starts_with($exception->getMessage(), '30001')) {
            // ignore
        }
    }
});

afterAll(function (): void {
    $client = new ClientBuilder()
        ->withMode(Mode::Test)
        ->build();

    $client->testdata()->subject()->remove([
        'subjectNip' => Env::string('NIP_2'),
    ]);
});

test('send an invoice for NIP_2 and export it as NIP_2', function (): void {
    /** @var AbstractTestCase $this */
    /** @var array<string, string> $_ENV */

    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $client = $this->createClient(encryptionKey: $encryptionKey);

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $client->sessions()->online()->open([
        'formCode' => 'FA (3)',
    ])->object();

    $fakturaFixture = new FakturaSprzedazyTowaruFixture()
        ->withNip(Env::string('NIP_1'))
        ->withForNip(Env::string('NIP_2'))
        ->withTodayDate()
        ->withRandomInvoiceNumber();

    $faktura = Faktura::from($fakturaFixture->data);

    /** @var object{referenceNumber: string} $sendResponse */
    $sendResponse = $client->sessions()->online()->send([
        'faktura' => $faktura,
        'referenceNumber' => $openResponse->referenceNumber,
    ])->object();

    $client->sessions()->online()->close([
        'referenceNumber' => $openResponse->referenceNumber
    ]);

    /** @var object{status: object{code: int}, referenceNumber: string, upoDownloadUrl: string, ksefNumber: string} $statusSessionResponse */
    $statusSessionResponse = Utility::retry(function (int $attempts) use ($client, $openResponse, $sendResponse) {
        /** @var object{status: object{code: int}, permanentStorageDate: string, referenceNumber: string, upoDownloadUrl: string} $statusResponse */
        $statusResponse = $client->sessions()->invoices()->status([
            'referenceNumber' => $openResponse->referenceNumber,
            'invoiceReferenceNumber' => $sendResponse->referenceNumber
        ])->object();

        try {
            expect($statusResponse->status->code)->toBe(200);
            expect($statusResponse)->toHaveProperty('permanentStorageDate');

            return $statusResponse;
        } catch (Throwable $exception) {
            if ($attempts > 5) {
                throw $exception;
            }
        }
    });

    $this->revokeCurrentSession($client);

    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $client = $this->createClient(
        identifier: Env::string('NIP_2'),
        certificatePath: Env::string('CERTIFICATE_PATH_2'),
        certificatePassphrase: Env::string('CERTIFICATE_PASSPHRASE_2'),
        encryptionKey: $encryptionKey
    );

    /** @var object{referenceNumber: string} $initResponse */
    $initResponse = $client->invoices()->exports()->init([
        'filters' => [
            'subjectType' => 'Subject2',
            'dateRange' => [
                'dateType' => 'PermanentStorage',
                'from' => new DateTimeImmutable('-5 minutes', new DateTimeZone('UTC')),
                'to' => new DateTimeImmutable('+5 minutes', new DateTimeZone('UTC'))
            ],
        ]
    ])->object();

    /** @var object{status: object{code: int}, package: object{parts: array<int, object{url: string}>}} $statusResponse */
    $statusResponse = Utility::retry(function (int $attempts) use ($client, $initResponse) {
        /** @var object{status: object{code: int}, package: object{parts: array<int, object{url: string}>}} $statusResponse */
        $statusResponse = $client->invoices()->exports()->status([
            'referenceNumber' => $initResponse->referenceNumber
        ])->object();

        try {
            expect($statusResponse->status->code)->toBe(200);

            return $statusResponse;
        } catch (Throwable $exception) {
            if ($attempts > 2) {
                throw $exception;
            }
        }
    });

    expect($statusResponse->package->parts)->not()->toBeEmpty();

    $decryptDocumentHandler = new DecryptDocumentHandler();

    $zipContents = '';

    // Downloading...
    foreach ($statusResponse->package->parts as $part) {
        /** @var string $contents */
        $contents = file_get_contents($part->url);

        $contents = $decryptDocumentHandler->handle(new DecryptDocumentAction(
            encryptionKey: $encryptionKey,
            document: $contents
        ));

        $zipContents .= $contents;
    }

    file_put_contents(Utility::basePath("var/zip/invoices.zip"), $zipContents);

    $zip = new ZipArchive();

    $openZip = $zip->open('var/zip/invoices.zip');

    expect($openZip)->toBeTrue();

    $exists = $zip->locateName("{$statusSessionResponse->ksefNumber}.xml");

    expect($exists)->toBeInt();

    $zip->close();
});
