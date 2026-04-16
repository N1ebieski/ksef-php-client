<?php

use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use N1ebieski\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesAction;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesHandler;
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\DTOs\QRCodes;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Faktura as FakturaRR;
use N1ebieski\KSEFClient\Exceptions\HttpClient\BadRequestException;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaRR\FakturaSprzedazyTowaruRolniczegoFixture;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\QRCode;
use N1ebieski\KSEFClient\ValueObjects\Requests\KsefNumber;
use N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Authorizations\AuthorizationPermissionType;

/** @var AbstractTestCase $this */

beforeAll(function (): void {
    $client = (new ClientBuilder())
        ->withMode(Mode::Test)
        ->build();

    try {
        $client->testdata()->person()->create([
            'nip' => $_ENV['NIP_2'],
            'pesel' => $_ENV['PESEL_2'],
            'description' => 'Subject who gives RRInvoicing permission',
        ])->status();
    } catch (BadRequestException $exception) {
        if (str_starts_with($exception->getMessage(), '30001')) {
            // ignore
        }
    }
});

test('send the RR invoice as NIP_1 as Podmiot2, check for UPO and generate QR code', function (): void {
    /** @var AbstractTestCase $this */
    /** @var array<string, string> $_ENV */

    $clientNip2 = $this->createClient(
        identifier: $_ENV['NIP_2'],
        certificatePath: $_ENV['CERTIFICATE_PATH_2'],
        certificatePassphrase: $_ENV['CERTIFICATE_PASSPHRASE_2']
    );

    /** @var object{referenceNumber: string} $grantsResponse */
    $grantsResponse = $clientNip2->permissions()->authorizations()->grants([
        'subjectIdentifierGroup' => [
            'nip' => $_ENV['NIP_1']
        ],
        'permission' => 'RRInvoicing',
        'description' => 'Give RRInvoicing permission to NIP_1',
        'subjectDetails' => [
            'fullName' => 'Jan Kowalski'
        ]
    ])->object();

    Utility::retry(function (int $attempts) use ($clientNip2, $grantsResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string} $statusResponse */
        $statusResponse = $clientNip2->permissions()->operations()->status([
            'referenceNumber' => $grantsResponse->referenceNumber,
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

    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $client = $this->createClient(encryptionKey: $encryptionKey);

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $client->sessions()->online()->open([
        'formCode' => 'FA_RR (1)',
    ])->object();

    $fakturaFixture = (new FakturaSprzedazyTowaruRolniczegoFixture())
        ->withForNip($_ENV['NIP_1'])
        ->withNip($_ENV['NIP_2'])
        ->withTodayDate()
        ->withRandomInvoiceNumber();

    $faktura = FakturaRR::from($fakturaFixture->data);

    /** @var object{referenceNumber: string} $sendResponse */
    $sendResponse = $client->sessions()->online()->send([
        'faktura' => $faktura,
        'formCode' => 'FA_RR (1)',
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
        invoiceCreatedAt: $faktura->fakturaRR->p_4B->value,
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

    /** @var object{authorizationGrants: array<int, object{id: string, authorizationScope: string}>} $queryResponse */
    $queryResponse = $client->permissions()->query()->authorizations()->grants([
        'queryType' => 'Received',
        'authorizingIdentifierGroup' => [
            'nip' => $_ENV['NIP_2']
        ],
    ])->object();

    expect($queryResponse)->toHaveProperty('authorizationGrants');

    expect($queryResponse->authorizationGrants)->toBeArray()->not->toBeEmpty();

    $permissions = array_filter(
        $queryResponse->authorizationGrants,
        fn (object $permission): bool => $permission->authorizationScope === AuthorizationPermissionType::RRInvoicing->value
    );

    expect($permissions)->toBeArray()->not->toBeEmpty();

    expect($permissions[0])->toHaveProperty('id');

    expect($permissions[0]->id)->toBeString();

    /** @var object{referenceNumber: string} $revokePermissionResponse */
    $revokePermissionResponse = $clientNip2->permissions()->authorizations()->revoke([
        'permissionId' => $permissions[0]->id
    ])->object();

    Utility::retry(function (int $attempts) use ($clientNip2, $revokePermissionResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string} $statusResponse */
        $statusResponse = $clientNip2->permissions()->operations()->status([
            'referenceNumber' => $revokePermissionResponse->referenceNumber,
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

    $this->revokeCurrentSession($client);
    $this->revokeCurrentSession($clientNip2);
});
