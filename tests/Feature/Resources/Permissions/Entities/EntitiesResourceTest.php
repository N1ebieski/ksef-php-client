<?php

use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Exceptions\HttpClient\BadRequestException;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Env;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Send\SendRequestFixture;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\Requests\Permissions\Query\Personal\PersonalPermissionType;
use N1ebieski\KSEFClient\ValueObjects\Requests\Testdata\Subject\SubjectType;

/** @var AbstractTestCase $this */

beforeAll(function (): void {
    $client = new ClientBuilder()
        ->withMode(Mode::Test)
        ->build();

    foreach (['NIP_2', 'NIP_3'] as $nip) {
        try {
            $client->testdata()->subject()->create([
                'subjectNip' => Env::string($nip),
                'subjectType' => SubjectType::EnforcementAuthority,
                'description' => 'Subject who gives InvoiceWrite permission',
            ])->status();
        } catch (BadRequestException $exception) {
            if (str_starts_with($exception->getMessage(), '30001')) {
                // ignore
            }
        }
    }
});

test('send invoice as NIP_2 when NIP_2 gave InvoiceWrite permission', function (): void {
    /** @var AbstractTestCase $this */
    $clientNip2 = $this->createClient(
        identifier: Env::string('NIP_2'),
        certificatePath: Env::string('CERTIFICATE_PATH_2'),
        certificatePassphrase: Env::string('CERTIFICATE_PASSPHRASE_2')
    );

    /** @var object{referenceNumber: string} $grantsResponse */
    $grantsResponse = $clientNip2->permissions()->entities()->grants([
        'subjectIdentifierGroup' => [
            'nip' => Env::string('NIP_1')
        ],
        'permissions' => [
            [
                'type' => 'InvoiceWrite'
            ]
        ],
        'description' => 'Give InvoiceWrite permission to NIP_1',
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

    $clientNip1 = $this->createClient(
        identifier: Env::string('NIP_2'),
        encryptionKey: $encryptionKey
    );

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $clientNip1->sessions()->online()->open([
        'formCode' => 'FA (3)',
    ])->object();

    $fakturaFixture = new FakturaSprzedazyTowaruFixture()
        ->withNip(Env::string('NIP_2'))
        ->withTodayDate()
        ->withRandomInvoiceNumber();

    $fixture = new SendRequestFixture()->withFakturaFixture($fakturaFixture);

    /** @var object{referenceNumber: string} $sendResponse */
    $sendResponse = $clientNip1->sessions()->online()->send([
        ...$fixture->data,
        'referenceNumber' => $openResponse->referenceNumber,
    ])->object();

    $clientNip1->sessions()->online()->close([
        'referenceNumber' => $openResponse->referenceNumber
    ]);

    Utility::retry(function (int $attempts) use ($clientNip1, $openResponse, $sendResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string} $statusResponse */
        $statusResponse = $clientNip1->sessions()->invoices()->status([
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

    /** @var object{permissions: array<int, object{id: string, permissionScope: string}>} $queryResponse */
    $queryResponse = $clientNip1->permissions()->query()->personal()->grants([
        'contextIdentifierGroup' => [
            'nip' => Env::string('NIP_2')
        ],
    ])->object();

    expect($queryResponse)->toHaveProperty('permissions');

    expect($queryResponse->permissions)->not()->toBeEmpty();

    $permissions = array_filter(
        $queryResponse->permissions,
        fn (object $permission): bool => $permission->permissionScope === PersonalPermissionType::InvoiceWrite->value
    );

    expect($permissions)->not()->toBeEmpty();

    expect($permissions[0])->toHaveProperty('id');

    /** @var object{referenceNumber: string} $revokePermissionResponse */
    $revokePermissionResponse = $clientNip2->permissions()->common()->revoke([
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

    $this->revokeCurrentSession($clientNip1);
    $this->revokeCurrentSession($clientNip2);
});

test('send invoice as NIP_3 when NIP_3 gave canDelegate InvoiceWrite permission', function (): void {
    /** @var AbstractTestCase $this */
    $clientNip3 = $this->createClient(
        identifier: Env::string('NIP_3'),
        certificatePath: Env::string('CERTIFICATE_PATH_3'),
        certificatePassphrase: Env::string('CERTIFICATE_PASSPHRASE_3')
    );

    /** @var object{referenceNumber: string} $grantsResponse */
    $grantsResponse = $clientNip3->permissions()->entities()->grants([
        'subjectIdentifierGroup' => [
            'nip' => Env::string('NIP_2')
        ],
        'permissions' => [
            [
                'type' => 'InvoiceWrite',
                'canDelegate' => true
            ]
        ],
        'description' => 'Give InvoiceWrite permission to NIP_2',
        'subjectDetails' => [
            'fullName' => 'Bożydar Kowalski'
        ]
    ])->object();

    Utility::retry(function (int $attempts) use ($clientNip3, $grantsResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string} $statusResponse */
        $statusResponse = $clientNip3->permissions()->operations()->status([
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
        identifier: Env::string('NIP_2'),
        certificatePath: Env::string('CERTIFICATE_PATH_2'),
        certificatePassphrase: Env::string('CERTIFICATE_PASSPHRASE_2')
    );

    /** @var object{referenceNumber: string} $grantsResponse */
    $grantsResponse = $clientNip2->permissions()->indirect()->grants([
        'subjectIdentifierGroup' => [
            'nip' => Env::string('NIP_1')
        ],
        'targetIdentifierGroup' => [
            'nip' => Env::string('NIP_3')
        ],
        'permissions' => [
            'InvoiceWrite'
        ],
        'description' => 'Give NIP_3 InvoiceWrite permission to NIP_1',
        'subjectDetails' => [
            'personById' => [
                'firstName' => 'Jan',
                'lastName' => 'Kowalski'
            ]
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

    $clientNip1 = $this->createClient(
        identifier: Env::string('NIP_3'),
        encryptionKey: $encryptionKey
    );

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $clientNip1->sessions()->online()->open([
        'formCode' => 'FA (3)',
    ])->object();

    $fakturaFixture = new FakturaSprzedazyTowaruFixture()
        ->withNip(Env::string('NIP_3'))
        ->withTodayDate()
        ->withRandomInvoiceNumber();

    $fixture = new SendRequestFixture()->withFakturaFixture($fakturaFixture);

    /** @var object{referenceNumber: string} $sendResponse */
    $sendResponse = $clientNip1->sessions()->online()->send([
        ...$fixture->data,
        'referenceNumber' => $openResponse->referenceNumber,
    ])->object();

    $clientNip1->sessions()->online()->close([
        'referenceNumber' => $openResponse->referenceNumber
    ]);

    Utility::retry(function (int $attempts) use ($clientNip1, $openResponse, $sendResponse) {
        /** @var object{status: object{code: int}, referenceNumber: string} $statusResponse */
        $statusResponse = $clientNip1->sessions()->invoices()->status([
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

    /** @var object{permissions: array<int, object{id: string}>} $queryResponse */
    $queryResponse = $clientNip1->permissions()->query()->personal()->grants([
        'targetIdentifierGroup' => [
            'nip' => Env::string('NIP_3')
        ],
    ])->object();

    /** @var object{referenceNumber: string} $revokePermissionResponse */
    $revokePermissionResponse = $clientNip2->permissions()->common()->revoke([
        'permissionId' => $queryResponse->permissions[0]->id
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

    $this->revokeCurrentSession($clientNip1);
    $this->revokeCurrentSession($clientNip2);
    $this->revokeCurrentSession($clientNip3);
});
