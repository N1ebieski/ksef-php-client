<?php

use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use N1ebieski\KSEFClient\Actions\ConvertCertificateToPkcs12\ConvertCertificateToPkcs12Action;
use N1ebieski\KSEFClient\Actions\ConvertCertificateToPkcs12\ConvertCertificateToPkcs12Handler;
use N1ebieski\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemAction;
use N1ebieski\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemHandler;
use N1ebieski\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use N1ebieski\KSEFClient\Actions\ConvertPemToDer\ConvertPemToDerAction;
use N1ebieski\KSEFClient\Actions\ConvertPemToDer\ConvertPemToDerHandler;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesAction;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesHandler;
use N1ebieski\KSEFClient\DTOs\DN;
use N1ebieski\KSEFClient\DTOs\QRCodes;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\Factories\CSRFactory;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use N1ebieski\KSEFClient\ValueObjects\CertificateSerialNumber;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\PrivateKeyType;
use N1ebieski\KSEFClient\ValueObjects\QRCode;
use N1ebieski\KSEFClient\ValueObjects\Requests\KsefNumber;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array<PrivateKeyType>>
 */
dataset('privateKeyTypeProvider', fn (): array => [
    'RSA' => [PrivateKeyType::RSA],
    'EC' => [PrivateKeyType::EC],
]);

test('send an invoice, check for UPO and generate QR code', function (): void {
    /** @var AbstractTestCase $this */
    /** @var array<string, string> $_ENV */

    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $client = $this->createClient(encryptionKey: $encryptionKey);

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $client->sessions()->online()->open([
        'formCode' => 'FA (3)',
    ])->object();

    $fakturaFixture = (new FakturaSprzedazyTowaruFixture())
        ->withNip($_ENV['NIP_1'])
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

    expect($statusResponse)->toHaveProperty('upoDownloadUrl');
    expect($statusResponse->upoDownloadUrl)->toBeString();

    expect($statusResponse)->toHaveProperty('ksefNumber');
    expect($statusResponse->ksefNumber)->toBeString();

    $generateQRCodesHandler = new GenerateQRCodesHandler(
        qrCodeBuilder: (new QrCodeBuilder())
            ->roundBlockSizeMode(RoundBlockSizeMode::Enlarge)
            ->labelFont(new OpenSans(size: 12)),
        convertEcdsaDerToRawHandler: new ConvertEcdsaDerToRawHandler()
    );

    $ksefNumber = KsefNumber::from($statusResponse->ksefNumber);

    $qrCodes = $generateQRCodesHandler->handle(new GenerateQRCodesAction(
        nip: $faktura->podmiot1->daneIdentyfikacyjne->nip,
        invoiceCreatedAt: $faktura->fa->p_1->value,
        document: $faktura->toXml(),
        mode: Mode::Test,
        ksefNumber: $ksefNumber
    ));

    expect($qrCodes)
        ->toBeInstanceOf(QRCodes::class)
        ->toHaveProperty('code1');

    expect($qrCodes->code1)
        ->toBeInstanceOf(QRCode::class)
        ->toHaveProperty('raw');

    expect($qrCodes->code1->raw)->toBeString();

    $this->revokeCurrentSession($client);
});

test('create an offline invoice and send it', function (PrivateKeyType $privateKeyType): void {
    /**
     * @var AbstractTestCase $this
     * @var array<string, string> $_ENV
     */
    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $client = $this->createClient(encryptionKey: $encryptionKey);

    $dataResponse = $client->certificates()->enrollments()->data()->json();

    $dn = DN::from($dataResponse);

    $csr = CSRFactory::make($dn, $privateKeyType);

    $csrToDer = (new ConvertPemToDerHandler())->handle(new ConvertPemToDerAction($csr->raw));

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

    $certificateToPem = (new ConvertDerToPemHandler())->handle(
        new ConvertDerToPemAction($certificate, 'CERTIFICATE')
    );

    $certificateToPkcs12 = (new ConvertCertificateToPkcs12Handler())->handle(
        new ConvertCertificateToPkcs12Action(
            certificate: CertificateFactory::makeFromPkcs8($certificateToPem, $csr->privateKey),
            passphrase: $_ENV['KSEF_OFFLINE_CERTIFICATE_PASSPHRASE_1']
        )
    );

    file_put_contents(Utility::basePath($_ENV['KSEF_OFFLINE_CERTIFICATE_PATH_1']), $certificateToPkcs12);

    $certificate = CertificateFactory::makeFromCertificatePath(
        CertificatePath::from(
            Utility::basePath($_ENV['KSEF_OFFLINE_CERTIFICATE_PATH_1']),
            $_ENV['KSEF_OFFLINE_CERTIFICATE_PASSPHRASE_1']
        )
    );

    $fakturaFixture = (new FakturaSprzedazyTowaruFixture())
        ->withNip($_ENV['NIP_1'])
        ->withTodayDate()
        ->withRandomInvoiceNumber();

    $faktura = Faktura::from($fakturaFixture->data);

    $generateQRCodesHandler = new GenerateQRCodesHandler(
        qrCodeBuilder: (new QrCodeBuilder())
            ->roundBlockSizeMode(RoundBlockSizeMode::Enlarge)
            ->labelFont(new OpenSans(size: 12)),
        convertEcdsaDerToRawHandler: new ConvertEcdsaDerToRawHandler()
    );

    $contextIdentifierGroup = ContextIdentifierGroup::fromIdentifier(NIP::from($_ENV['NIP_1']));

    $qrCodes = $generateQRCodesHandler->handle(new GenerateQRCodesAction(
        nip: $faktura->podmiot1->daneIdentyfikacyjne->nip,
        invoiceCreatedAt: $faktura->fa->p_1->value,
        document: $faktura->toXml(),
        mode: Mode::Test,
        certificate: $certificate,
        certificateSerialNumber: $certificateSerialNumber,
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

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $client->sessions()->online()->open([
        'formCode' => 'FA (3)',
    ])->object();

    /** @var object{referenceNumber: string} $sendResponse */
    $sendResponse = $client->sessions()->online()->send([
        'faktura' => $faktura,
        'offlineMode' => true,
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

    $response = $this->client->get((string) $qrCode1->url);

    expect($response->getStatusCode())->toBe(200);

    $contents = $response->getBody()->getContents();

    expect($contents)
        ->toContain('Faktura znajduje się w KSeF')
        ->toContain('Tryb wystawienia faktury')
        ->toContain('Offline');

    $revokeCertificate = $client->certificates()->revoke([
        'certificateSerialNumber' => (string) $certificateSerialNumber
    ])->status();

    expect($revokeCertificate)->toBe(204);

    $this->revokeCurrentSession($client);
})->with('privateKeyTypeProvider');
