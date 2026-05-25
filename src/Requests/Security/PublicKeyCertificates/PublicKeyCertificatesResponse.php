<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Security\PublicKeyCertificates;

use DateTimeImmutable;
use DateTimeZone;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Requests\Security\PublicKeyCertificates\PublicKeyCertificatesResponseInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyCertificateUsage;
use N1ebieski\KSEFClient\ValueObjects\Support\KeyType;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

final class PublicKeyCertificatesResponse implements PublicKeyCertificatesResponseInterface
{
    public readonly BaseResponseInterface $baseResponse;

    public function __construct(private readonly ResponseInterface $response)
    {
        $this->baseResponse = $response->baseResponse;
    }

    public function getFirstByPublicKeyCertificateUsage(PublicKeyCertificateUsage $type): ?string
    {
        $certificate = $this->getFirstCertificateByUsage($type);

        return $certificate?->certificate;
    }

    public function getFirstCertificateByUsage(PublicKeyCertificateUsage $type): ?object
    {
        /** @var array<int, object{certificate: string, publicKeyId?: string, validFrom: string, validTo: string, usage: array<int, string>}> */
        $certificates = $this->object();

        usort($certificates, static fn (object $a, object $b): int => strcmp($b->validTo, $a->validTo));

        foreach ($certificates as $certificate) {
            if ( ! in_array($type->value, $certificate->usage)) {
                continue;
            }

            if (new DateTimeImmutable($certificate->validTo) < new DateTimeImmutable(timezone: new DateTimeZone('UTC'))) {
                continue;
            }

            if (new DateTimeImmutable($certificate->validFrom) > new DateTimeImmutable(timezone: new DateTimeZone('UTC'))) {
                continue;
            }

            return $certificate;
        }

        return null;
    }

    public function throwExceptionIfError(): void
    {
        $this->response->throwExceptionIfError();
    }

    public function status(): int
    {
        return $this->response->status();
    }

    public function header(string $name): ?string
    {
        return $this->response->header($name);
    }

    public function headers(): array
    {
        return $this->response->headers();
    }

    public function json(?string $key = null, mixed $default = null): mixed
    {
        return $this->response->json($key, $default);
    }

    public function object(): object | array
    {
        return $this->response->object();
    }

    public function body(): string
    {
        return $this->response->body();
    }

    public function data(): string | array
    {
        return $this->response->data();
    }

    public function toArray(KeyType $keyType = KeyType::Camel, array $only = []): array
    {
        return $this->response->toArray($keyType, $only);
    }
}
