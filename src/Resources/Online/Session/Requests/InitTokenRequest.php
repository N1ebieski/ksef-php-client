<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Session\Requests;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\XmlSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\ValueObjects\Challenge;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\ValueObjects\EncryptedToken;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\SystemCode;
use N1ebieski\KSEFClient\Resources\Request;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use RuntimeException;
use SensitiveParameter;

final readonly class InitTokenRequest extends Request implements XmlSerializableInterface
{
    public function __construct(
        #[SensitiveParameter]
        public EncryptedToken $encryptedToken,
        #[SensitiveParameter]
        public Challenge $challenge,
        public NIP $nip,
        public SystemCode $systemCode = SystemCode::Fa2
    ) {
    }

    public function toXml(): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $initSessionTokenRequest = $dom->createElement('InitSessionTokenRequest');
        $initSessionTokenRequest->setAttribute('xmlns', 'http://ksef.mf.gov.pl/schema/gtw/svc/online/auth/request/2021/10/01/0001');
        $initSessionTokenRequest->setAttribute('xmlns:request.auth', 'http://ksef.mf.gov.pl/schema/gtw/svc/online/auth/request/2021/10/01/0001');
        $initSessionTokenRequest->setAttribute('xmlns:types', 'http://ksef.mf.gov.pl/schema/gtw/svc/types/2021/10/01/0001');
        $initSessionTokenRequest->setAttribute('xmlns:online.types', 'http://ksef.mf.gov.pl/schema/gtw/svc/online/types/2021/10/01/0001');
        $initSessionTokenRequest->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $dom->appendChild($initSessionTokenRequest);

        $context = $dom->createElement('Context');
        $initSessionTokenRequest->appendChild($context);

        $challenge = $dom->createElement('online.types:Challenge');
        $challenge->appendChild($dom->createTextNode((string) $this->challenge));

        $context->appendChild($challenge);

        $identifier = $dom->createElement('online.types:Identifier');
        $identifier->setAttribute('xsi:type', 'types:SubjectIdentifierByCompanyType');

        $context->appendChild($identifier);

        $id = $dom->createElement('types:Identifier');
        $id->appendChild($dom->createTextNode((string) $this->nip));

        $identifier->appendChild($id);

        $documentType = $dom->createElement('online.types:DocumentType');
        $context->appendChild($documentType);

        $service = $dom->createElement('types:Service');
        $service->appendChild($dom->createTextNode('KSeF'));

        $documentType->appendChild($service);

        $formCode = $dom->createElement('types:FormCode');
        $documentType->appendChild($formCode);

        $systemCode = $dom->createElement('types:SystemCode');
        $systemCode->appendChild($dom->createTextNode((string) $this->systemCode->value));

        $formCode->appendChild($systemCode);

        $schemaVersion = $dom->createElement('types:SchemaVersion');
        $schemaVersion->appendChild($dom->createTextNode($this->systemCode->getSchemaVersion()));

        $formCode->appendChild($schemaVersion);

        $targetNamespace = $dom->createElement('types:TargetNamespace');
        $targetNamespace->appendChild($dom->createTextNode($this->systemCode->getTargetNamespace()));

        $formCode->appendChild($targetNamespace);

        $value = $dom->createElement('types:Value');
        $value->appendChild($dom->createTextNode('FA'));

        $formCode->appendChild($value);

        $token = $dom->createElement('online.types:Token');
        $token->appendChild($dom->createTextNode((string) $this->encryptedToken));

        $context->appendChild($token);

        $xml = $dom->saveXML();

        return $xml ?: throw new RuntimeException('Unable to save XML');
    }
}
