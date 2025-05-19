<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Session\InitToken;

use DateTimeImmutable;
use DOMDocument;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\Challenge;
use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\EncryptedToken;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\SystemCode;
use N1ebieski\KSEFClient\Requests\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\ValueObjects\ApiToken;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\KSEFPublicKeyPath;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use RuntimeException;
use SensitiveParameter;

final readonly class InitTokenRequest extends AbstractRequest
{
    public function __construct(
        #[SensitiveParameter]
        public ApiToken $apiToken,
        #[SensitiveParameter]
        public Challenge $challenge,
        #[SensitiveParameter]
        public DateTimeImmutable $timestamp,
        public KSEFPublicKeyPath $ksefPublicKeyPath,
        public NIP $nip,
        public SystemCode $systemCode = SystemCode::Fa2
    ) {
    }

    public function toXml(EncryptedToken $encryptedToken, ?DOMDocument $encryptionDom = null): string
    {
        return $this->toDom($encryptedToken, $encryptionDom)->saveXML() ?: throw new RuntimeException(
            'Unable to serialize to XML'
        );
    }

    public function toDom(EncryptedToken $encryptedToken, ?DOMDocument $encryptionDom = null): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $initSessionTokenRequest = $dom->createElementNS((string) XmlNamespace::KsefOnlineAuthRequest->value, 'InitSessionTokenRequest');
        $initSessionTokenRequest->setAttribute('xmlns:types', (string) XmlNamespace::KsefTypes->value);
        $initSessionTokenRequest->setAttribute('xmlns:online.types', (string) XmlNamespace::KsefOnlineTypes->value);
        $initSessionTokenRequest->setAttribute('xmlns:xsi', (string) XmlNamespace::Xsi->value);

        $dom->appendChild($initSessionTokenRequest);

        $context = $dom->createElementNS((string) XmlNamespace::KsefOnlineAuthRequest->value, 'Context');
        $initSessionTokenRequest->appendChild($context);

        $challenge = $dom->createElementNS((string) XmlNamespace::KsefOnlineTypes->value, 'online.types:Challenge');
        $challenge->appendChild($dom->createTextNode((string) $this->challenge));

        $context->appendChild($challenge);

        $identifier = $dom->createElementNS((string) XmlNamespace::KsefOnlineTypes->value, 'online.types:Identifier');
        $identifier->setAttribute('xsi:type', 'types:SubjectIdentifierByCompanyType');

        $context->appendChild($identifier);

        $id = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Identifier');
        $id->appendChild($dom->createTextNode((string) $this->nip));

        $identifier->appendChild($id);

        $documentType = $dom->createElementNS((string) XmlNamespace::KsefOnlineTypes->value, 'online.types:DocumentType');
        $context->appendChild($documentType);

        $service = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Service');
        $service->appendChild($dom->createTextNode('KSeF'));

        $documentType->appendChild($service);

        $formCode = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:FormCode');
        $documentType->appendChild($formCode);

        $systemCode = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:SystemCode');
        $systemCode->appendChild($dom->createTextNode((string) $this->systemCode->value));

        $formCode->appendChild($systemCode);

        $schemaVersion = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:SchemaVersion');
        $schemaVersion->appendChild($dom->createTextNode($this->systemCode->getSchemaVersion()));

        $formCode->appendChild($schemaVersion);

        $targetNamespace = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:TargetNamespace');
        $targetNamespace->appendChild($dom->createTextNode($this->systemCode->getTargetNamespace()));

        $formCode->appendChild($targetNamespace);

        $value = $dom->createElementNS((string) XmlNamespace::KsefTypes->value, 'types:Value');
        $value->appendChild($dom->createTextNode('FA'));

        $formCode->appendChild($value);

        if ($encryptionDom !== null) {
            $encryption = $dom->importNode($encryptionDom->documentElement, true);

            $context->appendChild($encryption);
        }

        $token = $dom->createElementNS((string) XmlNamespace::KsefOnlineTypes->value, 'online.types:Token');
        $token->appendChild($dom->createTextNode((string) $encryptedToken));

        $context->appendChild($token);

        return $dom;
    }
}
