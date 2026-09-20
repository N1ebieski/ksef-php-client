<?php

use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Exceptions\HttpClient\BadRequestException;
use N1ebieski\KSEFClient\Factories\InternalIdFactory;
use N1ebieski\KSEFClient\Support\Env;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use N1ebieski\KSEFClient\ValueObjects\RefreshToken;
use N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Query\Personal\PersonalPermissionType;

/** @var AbstractTestCase $this */

beforeAll(function (): void {
    $client = new ClientBuilder()
        ->withMode(Mode::Test)
        ->build();

    try {
        $client->testdata()->person()->create([
            'nip' => Env::string('NIP_2'),
            'pesel' => Env::string('PESEL_2'),
            'description' => 'Subject who get InternalId permission',
        ])->status();
    } catch (BadRequestException $exception) {
        if (str_starts_with($exception->getMessage(), '30001')) {
            // ignore
        }
    }
});

test('create InternalId for person', function (): void {
    /** @var AbstractTestCase $this */
    /** @var array<string, string> $_ENV */

    $clientNip1 = $this->createClient();

    $internalId = InternalIdFactory::make(NIP::from(Env::string('NIP_1')), '1234');

    /** @var object{referenceNumber: string} $grantsResponse */
    $grantsResponse = $clientNip1->permissions()->subunits()->grants([
        'subjectIdentifierGroup' => [
            'pesel' => Env::string('PESEL_2')
        ],
        'contextIdentifierGroup' => [
            'internalId' => $internalId
        ],
        'description' => 'Create InternalId for PESEL_2',
        'subunitName' => 'Subunit for PESEL_2',
        'subjectDetails' => [
            'personById' => [
                'firstName' => Env::string('FIRST_NAME_2'),
                'lastName' => Env::string('LAST_NAME_2'),
            ]
        ]
    ])->object();

    Utility::retry(function (int $attempts) use ($clientNip1, $grantsResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string} $statusResponse */
        $statusResponse = $clientNip1->permissions()->operations()->status([
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

    $clientNip2 = $this->createClient(
        identifier: $internalId,
        certificatePath: Env::string('CERTIFICATE_PATH_2'),
        certificatePassphrase: Env::string('CERTIFICATE_PASSPHRASE_2')
    );

    $accessToken = $clientNip2->getAccessToken();
    $refreshToken = $clientNip2->getRefreshToken();

    expect($accessToken)->toBeInstanceOf(AccessToken::class);
    expect($accessToken?->validUntil)->toBeGreaterThan(new DateTimeImmutable());

    expect($refreshToken)->toBeInstanceOf(RefreshToken::class);
    expect($refreshToken?->validUntil)->toBeGreaterThan(new DateTimeImmutable('+6 days'));

    /** @var object{permissions: array<int, object{id: string, permissionScope: string}>} $queryResponse */
    $queryResponse = $clientNip1->permissions()->query()->subunits()->grants([
        'subunitIdentifierGroup' => [
            'internalId' => $internalId
        ],
    ])->object();

    expect($queryResponse)->toHaveProperty('permissions');

    expect($queryResponse->permissions)->not()->toBeEmpty();

    $permissions = array_filter(
        $queryResponse->permissions,
        fn (object $permission): bool => $permission->permissionScope === PersonalPermissionType::CredentialsManage->value
    );

    expect($permissions)->not()->toBeEmpty();

    expect($permissions[0])->toHaveProperty('id');

    /** @var object{referenceNumber: string} $revokePermissionResponse */
    $revokePermissionResponse = $clientNip1->permissions()->common()->revoke([
        'permissionId' => $permissions[0]->id
    ])->object();

    Utility::retry(function (int $attempts) use ($clientNip1, $revokePermissionResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string} $statusResponse */
        $statusResponse = $clientNip1->permissions()->operations()->status([
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

    $this->revokeCurrentSession($clientNip1);
    $this->revokeCurrentSession($clientNip2);
});
