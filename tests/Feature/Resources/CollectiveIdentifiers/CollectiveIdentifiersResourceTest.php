<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\AbstractFakturaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Requests\CompressionType;

/** @var AbstractTestCase $this */

test('create a collective identifier for invoices and list it as the buyer', function (): void {
    /**
     * @var AbstractTestCase $this
     * @var array<string, string> $_ENV
     */
    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $clientNip1 = $this->createClient(encryptionKey: $encryptionKey);

    /** @var array<int, FakturaSprzedazyTowaruFixture> $fakturyFixtures */
    $fakturyFixtures = array_map(
        fn (): AbstractFakturaFixture => (new FakturaSprzedazyTowaruFixture())
            ->withNip($_ENV['NIP_1'])
            ->withForNip($_ENV['NIP_2'])
            ->withTodayDate()
            ->withoutPayment()
            ->withRandomInvoiceNumber(),
        range(1, 2)
    );

    /** @var array<int, Faktura> $faktury */
    $faktury = array_map(
        fn (FakturaSprzedazyTowaruFixture $faktura): Faktura => Faktura::from($faktura->data),
        $fakturyFixtures
    );

    $openAndSendResponse = $clientNip1->sessions()->batch()->openAndSend([
        'formCode' => 'FA (3)',
        'faktury' => $faktury,
        'compressionType' => CompressionType::Zip,
    ]);

    /** @var object{referenceNumber: string} $openResponse */
    $openResponse = $openAndSendResponse->object();

    foreach ($openAndSendResponse->partUploadResponses as $partUploadResponse) {
        expect($partUploadResponse?->status())->toBe(201);
    }

    $clientNip1->sessions()->batch()->close([
        'referenceNumber' => $openResponse->referenceNumber,
    ]);

    Utility::retry(function (int $attempts) use ($clientNip1, $openResponse) {
        /** @var object{status: object{code: int}} $statusResponse */
        $statusResponse = $clientNip1->sessions()->status([
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

    /** @var object{invoices: array<int, object{ksefNumber: string, status: object{code: int}}>} $invoicesResponse */
    $invoicesResponse = $clientNip1->sessions()->invoices()->list([
        'referenceNumber' => $openResponse->referenceNumber,
    ])->object();

    foreach ($invoicesResponse->invoices as $invoice) {
        expect($invoice->status->code)->toBe(200);
    }

    $clientNip2 = $this->createClient(
        identifier: $_ENV['NIP_2'],
        certificatePath: $_ENV['CERTIFICATE_PATH_2'],
        certificatePassphrase: $_ENV['CERTIFICATE_PASSPHRASE_2']
    );

    /** @var array<int, array{ksefNumber: string, payment: array{amount: float, currency: string}, description: string}> $payments */
    $payments = array_map(
        fn (object $invoice): array => [
            'ksefNumber' => $invoice->ksefNumber,
            'payment' => [
                'amount' => 100.45,
                'currency' => 'PLN',
            ],
            'description' => 'Payment for invoice',
        ],
        $invoicesResponse->invoices
    );

    usort($payments, fn (array $a, array $b): int => $b['ksefNumber'] <=> $a['ksefNumber']);

    $createResponse = $clientNip2->collectiveIdentifiers()->create([
        'invoices' => $payments,
    ])->object();

    /** @var object{collectiveIdentifierNumber: string} $createResponse */
    expect($createResponse->collectiveIdentifierNumber)->toBeString();

    /** @var object{invoices: array<int, object{ksefNumber: string, collectiveIdentifierNumber: string, payment: object{amount: float|int, currency: string}, description: string|null, detailsHidden: bool}>} $invoicesResponse */
    $invoicesResponse = $clientNip1->collectiveIdentifiers()->invoices([
        'collectiveIdentifierNumbers' => [$createResponse->collectiveIdentifierNumber]
    ])->object();

    expect($invoicesResponse->invoices)->toHaveCount(2);

    $paymentsByKsefNumber = array_column($payments, null, 'ksefNumber');

    foreach ($invoicesResponse->invoices as $invoice) {
        $payment = $paymentsByKsefNumber[$invoice->ksefNumber];

        expect($invoice->payment->amount)
            ->toEqualWithDelta($payment['payment']['amount'], 0.01);
    }

    $this->revokeCurrentSession($clientNip1);
    $this->revokeCurrentSession($clientNip2);
});
