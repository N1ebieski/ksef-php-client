<?php

declare(strict_types=1);

use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use N1ebieski\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesByInvoiceHashAction;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesHandler;
use N1ebieski\KSEFClient\DTOs\QRCodes;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use N1ebieski\KSEFClient\ValueObjects\CertificateSerialNumber;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\QRCode;

test('generate qr codes by invoice hash', function (): void {
    /** @var array<string, string> $_ENV */

    $certificateSerialNumber = CertificateSerialNumber::from('014651EA9FD2407C');

    $certificate = CertificateFactory::makeFromCertificatePath(
        CertificatePath::from(Utility::basePath($_ENV['CERTIFICATE_PATH_1']), $_ENV['CERTIFICATE_PASSPHRASE_1'])
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

    $invoiceHash = hash('sha256', $faktura->toXml(), true);

    $qrCodes = $generateQRCodesHandler->handle(new GenerateQRCodesByInvoiceHashAction(
        nip: $faktura->podmiot1->daneIdentyfikacyjne->nip,
        invoiceCreatedAt: $faktura->fa->p_1->value,
        invoiceHash: $invoiceHash,
        mode: Mode::Test,
        certificate: $certificate,
        certificateSerialNumber: $certificateSerialNumber,
        contextIdentifierGroup: $contextIdentifierGroup
    ));

    expect($qrCodes)
        ->toBeInstanceOf(QRCodes::class)
        ->toHaveProperty('code1')
        ->toHaveProperty('code2');

    expect($qrCodes->code1)
        ->toBeInstanceOf(QRCode::class)
        ->toHaveProperty('raw');

    expect($qrCodes->code1->raw)->toBeString();

    expect($qrCodes->code2)
        ->toBeInstanceOf(QRCode::class)
        ->toHaveProperty('raw');

    expect($qrCodes->code2?->raw)->toBeString();
});
