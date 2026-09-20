<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Unit\Actions\GenerateQRCodes;

use DateTimeImmutable;
use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use N1ebieski\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\Adapters\EndroidV6QRCodeGenerator;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesByInvoiceHashAction;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesHandler;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\Support\Env;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use N1ebieski\KSEFClient\ValueObjects\CertificateSerialNumber;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\QRCode;
use RuntimeException;

test('generate qr codes by invoice hash', function (): void {
    $certificateSerialNumber = CertificateSerialNumber::from('014651EA9FD2407C');

    $certificate = CertificateFactory::makeFromCertificatePath(
        CertificatePath::from(Utility::basePath(Env::string('CERTIFICATE_PATH_1')), Env::string('CERTIFICATE_PASSPHRASE_1'))
    );

    $fakturaFixture = new FakturaSprzedazyTowaruFixture()
        ->withNip(Env::string('NIP_1'))
        ->withTodayDate()
        ->withRandomInvoiceNumber();

    $faktura = Faktura::from($fakturaFixture->data);

    $generateQRCodesHandler = new GenerateQRCodesHandler(
        qrCodeGenerator: new EndroidV6QRCodeGenerator(new QrCodeBuilder()),
        convertEcdsaDerToRawHandler: new ConvertEcdsaDerToRawHandler()
    );

    $contextIdentifierGroup = ContextIdentifierGroup::fromIdentifier(NIP::from(Env::string('NIP_1')));

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

    expect($qrCodes)->toHaveProperties(['code1', 'code2']);

    expect($qrCodes->code1)->toHaveProperty('raw');

    expect($qrCodes->code2)
        ->toBeInstanceOf(QRCode::class)
        ->toHaveProperty('raw');

    expect($qrCodes->code2?->raw)->toBeString();
});

test('ensure that handler does not leak the caption between subsequent calls', function (): void {
    $generateQRCodesHandler = new GenerateQRCodesHandler(
        qrCodeGenerator: new EndroidV6QRCodeGenerator(new QrCodeBuilder()),
        convertEcdsaDerToRawHandler: new ConvertEcdsaDerToRawHandler()
    );

    $action = fn (bool $captions): GenerateQRCodesByInvoiceHashAction => new GenerateQRCodesByInvoiceHashAction(
        nip: NIP::from('6669669234'),
        invoiceCreatedAt: new DateTimeImmutable('2026-09-16'),
        invoiceHash: hash('sha256', 'faktura', true),
        mode: Mode::Test,
        captions: $captions
    );

    $withCaptions = getimagesizefromstring($generateQRCodesHandler->handle($action(true))->code1->raw);
    $withoutCaptions = getimagesizefromstring($generateQRCodesHandler->handle($action(false))->code1->raw);

    if ($withCaptions === false || $withoutCaptions === false) {
        throw new RuntimeException('Unable to read the image size');
    }

    // The caption is rendered below the code, so it makes the image higher than wider
    expect($withCaptions[1])->toBeGreaterThan($withCaptions[0]);

    // The same builder without captions must not reuse the caption from the previous call
    expect($withoutCaptions[1])->toBe($withoutCaptions[0]);
});
