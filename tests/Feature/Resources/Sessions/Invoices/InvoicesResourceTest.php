<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Env;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Send\SendRequestFixture;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;

/** @var AbstractTestCase $this */

test('send an invoice, check if it is in the list', function (): void {
    /** @var AbstractTestCase $this */
    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $client = $this->createClient(encryptionKey: $encryptionKey);

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $client->sessions()->online()->open([
        'formCode' => 'FA (3)',
    ])->object();

    $fakturaFixture = new FakturaSprzedazyTowaruFixture()
        ->withNip(Env::string('NIP_1'))
        ->withTodayDate()
        ->withRandomInvoiceNumber();

    $fixture = new SendRequestFixture()->withFakturaFixture($fakturaFixture);

    /** @var object{referenceNumber: string} $sendResponse */
    $sendResponse = $client->sessions()->online()->send([
        ...$fixture->data,
        'referenceNumber' => $openResponse->referenceNumber,
    ])->object();

    $client->sessions()->online()->close([
        'referenceNumber' => $openResponse->referenceNumber
    ]);

    /** @var object{status: object{code: int}, referenceNumber: string, upoDownloadUrl: string, ksefNumber: string} $statusResponse */
    $statusResponse = Utility::retry(function (int $attempts) use ($client, $openResponse, $sendResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string, upoDownloadUrl: string} $statusResponse */
        $statusResponse = $client->sessions()->invoices()->status([
            'referenceNumber' => $openResponse->referenceNumber,
            'invoiceReferenceNumber' => $sendResponse->referenceNumber
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

    /** @var object{invoices: array<int, object{ksefNumber: string}>} $listResponse */
    $listResponse = $client->sessions()->invoices()->list([
        'referenceNumber' => $openResponse->referenceNumber,
    ])->object();

    expect($listResponse)->toHaveProperty('invoices');
    expect($listResponse->invoices)->toBeArray()->not()->toBeEmpty();

    $matches = array_filter(
        $listResponse->invoices,
        fn (object $invoice): bool => ($invoice->ksefNumber ?? null) === $statusResponse->ksefNumber
    );

    expect($matches)->toHaveCount(1);
});
