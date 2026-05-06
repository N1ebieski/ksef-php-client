<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response as PsrResponse;
use N1ebieski\KSEFClient\HttpClient\Response;
use N1ebieski\KSEFClient\Requests\Security\PublicKeyCertificates\PublicKeyCertificatesResponse;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyCertificateUsage;

test('returns certificate with latest validTo after sorting by usage', function (): void {
    $baseResponse = new PsrResponse(200, body: json_encode([
        [
            'certificate' => 'certificate-older-valid-to',
            'validFrom' => '2024-07-11T12:23:56.0154302+00:00',
            'validTo' => '2028-07-11T12:23:56.0154302+00:00',
            'usage' => [PublicKeyCertificateUsage::KsefTokenEncryption->value],
        ],
        [
            'certificate' => 'certificate-newer-valid-to',
            'validFrom' => '2024-07-11T12:23:56.0154302+00:00',
            'validTo' => '2029-07-11T12:23:56.0154302+00:00',
            'usage' => [PublicKeyCertificateUsage::KsefTokenEncryption->value],
        ],
    ], JSON_THROW_ON_ERROR));

    $response = new PublicKeyCertificatesResponse(new Response($baseResponse));

    $certificate = $response->getFirstCertificateByUsage(
        PublicKeyCertificateUsage::KsefTokenEncryption
    );

    expect($certificate)->not->toBeNull()
        ->and($certificate?->certificate)->toBe('certificate-newer-valid-to')
        ->and($certificate?->validTo)->toBe('2029-07-11T12:23:56.0154302+00:00');
});
