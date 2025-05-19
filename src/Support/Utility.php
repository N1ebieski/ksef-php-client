<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use Closure;
use RuntimeException;
use SensitiveParameter;

final class Utility
{
    public static function retry(Closure $closure, int $backoff = 10, int $retryUntil = 120): mixed
    {
        $seconds = 0;

        while (true) {
            sleep($backoff);

            $result = $closure();

            if ($result !== null) {
                return $result;
            }

            $seconds += $backoff;

            if ($seconds > $retryUntil) {
                throw new RuntimeException("Operation did not return a result after retrying for {$retryUntil} seconds.");
            }
        }
    }

    public static function hash(
        #[SensitiveParameter]
        string $document,
    ): array {
        $hashSHA = base64_encode(hash('sha256', $document, true));
        $fileSize = strlen($document);

        return [
            'hashSHA' => [
                'algorithm' => 'SHA-256',
                'encoding' => 'Base64',
                'value' => $hashSHA,
            ],
            'fileSize' => $fileSize,
        ];
    }
}
