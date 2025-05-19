<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Support\Concerns\HasToXml;
use SensitiveParameter;

final readonly class EncryptedKey extends AbstractValueObject implements XmlSerializableInterface, DomSerializableInterface
{
    use HasToXml;

    public function __construct(
        #[SensitiveParameter]
        public string $key,
        #[SensitiveParameter]
        public string $iv
    ) {
    }

    public static function from(string $key, string $iv): self
    {
        return new self($key, $iv);
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $encryption = $dom->createElementNS((string) XmlNamespace::KsefOnlineTypes->value, 'online.types:Encryption');

        $dom->appendChild($encryption);

        $encryptionKey = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'online.types:EncryptionKey');

        $encryption->appendChild($encryptionKey);

        $encoding = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Encoding', 'Base64');

        $encryptionKey->appendChild($encoding);

        $algorithm = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Algorithm', 'AES');

        $encryptionKey->appendChild($algorithm);

        $size = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Size', '256');

        $encryptionKey->appendChild($size);

        $value = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Value', $this->key);

        $encryptionKey->appendChild($value);

        $encryptionInitializationVector = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'online.types:EncryptionInitializationVector');

        $encryption->appendChild($encryptionInitializationVector);

        $encoding = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Encoding', 'Base64');

        $encryptionInitializationVector->appendChild($encoding);

        $bytes = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'online.types:Bytes', '16');

        $encryptionInitializationVector->appendChild($bytes);

        $value = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Value', $this->iv);

        $encryptionInitializationVector->appendChild($value);

        $encryptionAlgorithmKey = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'online.types:EncryptionAlgorithmKey');

        $encryption->appendChild($encryptionAlgorithmKey);

        $algorithm = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Algorithm', 'RSA');

        $encryptionAlgorithmKey->appendChild($algorithm);

        $mode = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Mode', 'ECB');

        $encryptionAlgorithmKey->appendChild($mode);

        $padding = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Padding', 'PKCS#1');

        $encryptionAlgorithmKey->appendChild($padding);

        $encryptionAlgorithmData = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'online.types:EncryptionAlgorithmData');

        $encryption->appendChild($encryptionAlgorithmData);

        $algorithm = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Algorithm', 'AES');

        $encryptionAlgorithmData->appendChild($algorithm);

        $mode = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Mode', 'CBC');

        $encryptionAlgorithmData->appendChild($mode);

        $padding = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Padding', 'PKCS#7');

        $encryptionAlgorithmData->appendChild($padding);

        return $dom;
    }
}
