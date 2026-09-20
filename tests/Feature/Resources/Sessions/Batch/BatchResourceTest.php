<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Feature\Resources\Sessions\Batch;

use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use N1ebieski\KSEFClient\Actions\ConvertCertificateToPkcs12\ConvertCertificateToPkcs12Action;
use N1ebieski\KSEFClient\Actions\ConvertCertificateToPkcs12\ConvertCertificateToPkcs12Handler;
use N1ebieski\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemAction;
use N1ebieski\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemHandler;
use N1ebieski\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use N1ebieski\KSEFClient\Actions\ConvertPemToDer\ConvertPemToDerAction;
use N1ebieski\KSEFClient\Actions\ConvertPemToDer\ConvertPemToDerHandler;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\Adapters\EndroidV6QRCodeGenerator;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesAction;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesHandler;
use N1ebieski\KSEFClient\DTOs\DN;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\Factories\CSRFactory;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Env;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\AbstractFakturaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use N1ebieski\KSEFClient\ValueObjects\CertificateSerialNumber;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\PrivateKeyType;
use N1ebieski\KSEFClient\ValueObjects\QRCode;
use N1ebieski\KSEFClient\ValueObjects\Requests\CompressionType;
use Throwable;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array<PrivateKeyType>>
 */
dataset('privateKeyTypeProvider', fn (): array => [
    'RSA' => [PrivateKeyType::RSA],
    'EC' => [PrivateKeyType::EC],
]);

test('send compressed invoices', function (CompressionType $compressionType): void {
    /**
     * @var AbstractTestCase $this
     */
    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $client = $this->createClient(encryptionKey: $encryptionKey);

    /** @var array<int, FakturaSprzedazyTowaruFixture> $fakturyFixtures */
    $fakturyFixtures = array_map(
        fn (): AbstractFakturaFixture => new FakturaSprzedazyTowaruFixture()
            ->withNip(Env::string('NIP_1'))
            ->withTodayDate()
            ->withRandomInvoiceNumber(),
        range(1, 3)
    );

    /** @var array<int, Faktura> $faktury */
    $faktury = array_map(fn (FakturaSprzedazyTowaruFixture $faktura): Faktura => Faktura::from($faktura->data), $fakturyFixtures);

    $openAndSendResponse = $client->sessions()->batch()->openAndSend([
        'formCode' => 'FA (3)',
        'faktury' => $faktury,
        'offlineMode' => true,
        'compressionType' => $compressionType
    ]);

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $openAndSendResponse->object();

    foreach ($openAndSendResponse->partUploadResponses as $partUploadResponse) {
        expect($partUploadResponse?->status())->toBe(201);
    }

    $client->sessions()->batch()->close([
        'referenceNumber' => $openResponse->referenceNumber
    ]);

    Utility::retry(function (int $attempts) use ($client, $openResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string, upoDownloadUrl: string} $statusResponse */
        $statusResponse = $client->sessions()->status([
            'referenceNumber' => $openResponse->referenceNumber,
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
})->with([
    CompressionType::Zip,
    CompressionType::TarGz,
]);

test('create offline invoices and send them', function (PrivateKeyType $privateKeyType): void {
    /**
     * @var AbstractTestCase $this
     */
    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $client = $this->createClient(encryptionKey: $encryptionKey);

    $dataResponse = $client->certificates()->enrollments()->data()->json();

    $dn = DN::from($dataResponse);

    $csr = CSRFactory::make($dn, $privateKeyType);

    $csrToDer = new ConvertPemToDerHandler()->handle(new ConvertPemToDerAction($csr->raw));

    /** @var object{referenceNumber: string} */
    $sendResponse = $client->certificates()->enrollments()->send([
        'certificateName' => 'testing',
        'certificateType' => 'Offline',
        'csr' => base64_encode($csrToDer),
    ])->object();

    /** @var object{status: object{code: int, description: string}, certificateSerialNumber: string} */
    $statusResponse = Utility::retry(function (int $attempts) use ($client, $sendResponse) {
        /** @var object{status: object{code: int, description: string}, certificateSerialNumber: string} */
        $statusResponse = $client->certificates()->enrollments()->status([
            'referenceNumber' => $sendResponse->referenceNumber
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

    $certificateSerialNumber = CertificateSerialNumber::from($statusResponse->certificateSerialNumber);

    /** @var object{certificates: array<object{certificate: string}>} */
    $retrieveResponse = $client->certificates()->retrieve([
        'certificateSerialNumbers' => [(string) $certificateSerialNumber]
    ])->object();

    $certificate = base64_decode((string) $retrieveResponse->certificates[0]->certificate);

    $certificateToPem = new ConvertDerToPemHandler()->handle(
        new ConvertDerToPemAction($certificate, 'CERTIFICATE')
    );

    $certificateToPkcs12 = new ConvertCertificateToPkcs12Handler()->handle(
        new ConvertCertificateToPkcs12Action(
            certificate: CertificateFactory::makeFromPkcs8($certificateToPem, $csr->privateKey),
            passphrase: Env::string('KSEF_OFFLINE_CERTIFICATE_PASSPHRASE_1')
        )
    );

    file_put_contents(Utility::basePath(Env::string('KSEF_OFFLINE_CERTIFICATE_PATH_1')), $certificateToPkcs12);

    $certificate = CertificateFactory::makeFromCertificatePath(
        CertificatePath::from(
            Utility::basePath(Env::string('KSEF_OFFLINE_CERTIFICATE_PATH_1')),
            Env::string('KSEF_OFFLINE_CERTIFICATE_PASSPHRASE_1')
        )
    );

    /** @var array<int, FakturaSprzedazyTowaruFixture> $fakturyFixtures */
    $fakturyFixtures = array_map(
        fn (): AbstractFakturaFixture => new FakturaSprzedazyTowaruFixture()
            ->withNip(Env::string('NIP_1'))
            ->withTodayDate()
            ->withRandomInvoiceNumber(),
        range(1, 3)
    );

    /** @var array<int, Faktura> $faktury */
    $faktury = array_map(fn (FakturaSprzedazyTowaruFixture $faktura): Faktura => Faktura::from($faktura->data), $fakturyFixtures);

    $generateQRCodesHandler = new GenerateQRCodesHandler(
        qrCodeGenerator: new EndroidV6QRCodeGenerator(new QrCodeBuilder()),
        convertEcdsaDerToRawHandler: new ConvertEcdsaDerToRawHandler()
    );

    $contextIdentifierGroup = ContextIdentifierGroup::fromIdentifier(NIP::from(Env::string('NIP_1')));

    $allQrCodes = [];

    foreach ($faktury as $faktura) {
        $qrCodes = $generateQRCodesHandler->handle(new GenerateQRCodesAction(
            nip: $faktura->podmiot1->daneIdentyfikacyjne->nip,
            invoiceCreatedAt: $faktura->fa->p_1->value,
            document: $faktura->toXml(),
            mode: Mode::Test,
            certificate: $certificate,
            contextIdentifierGroup: $contextIdentifierGroup
        ));

        $qrCode1 = $qrCodes->code1;

        expect($qrCode1->raw)->not()->toBeEmpty();
        expect($qrCodes->code2)->toBeInstanceOf(QRCode::class);

        /** @var QRCode $qrCode2 */
        $qrCode2 = $qrCodes->code2;

        expect($qrCode2->raw)->not()->toBeEmpty();

        $response = $this->client->get((string) $qrCode1->url);

        expect($response->getStatusCode())->toBe(200);

        $contents = $response->getBody()->getContents();

        expect($contents)->toContain('Faktura nie została znaleziona w KSeF');

        $response = $this->client->get((string) $qrCode2->url);

        expect($response->getStatusCode())->toBe(200);

        $contents = $response->getBody()->getContents();

        expect($contents)->toContain('Weryfikacja prawidłowa');

        $allQrCodes[] = $qrCodes;
    }

    $openAndSendResponse = $client->sessions()->batch()->openAndSend([
        'formCode' => 'FA (3)',
        'faktury' => $faktury,
        'offlineMode' => true
    ]);

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $openAndSendResponse->object();

    foreach ($openAndSendResponse->partUploadResponses as $partUploadResponse) {
        expect($partUploadResponse?->status())->toBe(201);
    }

    $client->sessions()->batch()->close([
        'referenceNumber' => $openResponse->referenceNumber
    ]);

    /** @var object{status: object{code: int}, referenceNumber: string, upoDownloadUrl: string} $statusResponse */
    $statusResponse = Utility::retry(function (int $attempts) use ($client, $openResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string, upoDownloadUrl: string} $statusResponse */
        $statusResponse = $client->sessions()->status([
            'referenceNumber' => $openResponse->referenceNumber,
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

    foreach ($allQrCodes as $qrCodes) {
        $response = $this->client->get((string) $qrCodes->code1->url);

        expect($response->getStatusCode())->toBe(200);

        $contents = $response->getBody()->getContents();

        expect($contents)
            ->toContain('Faktura znajduje się w KSeF')
            ->toContain('Tryb wystawienia faktury')
            ->toContain('Offline');
    }

    $revokeCertificate = $client->certificates()->revoke([
        'certificateSerialNumber' => (string) $certificateSerialNumber
    ])->status();

    expect($revokeCertificate)->toBe(204);

    $this->revokeCurrentSession($client);
})->with('privateKeyTypeProvider')->depends('send compressed invoices');
