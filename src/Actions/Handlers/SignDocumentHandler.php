<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\Handlers;

use DateTimeImmutable;
use DOMDocument;
use N1ebieski\KSEFClient\Actions\Handler;
use N1ebieski\KSEFClient\Actions\SignDocumentAction;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\XmlNamespace;
use N1ebieski\KSEFClient\Support\Str;
use RuntimeException;

/**
 * Special thanks to grafinet/xades-tools > https://github.com/grafinet/xades-tools
 * I could not use their dependency directly, but most of the logic in this class
 * is their authorship
 */
final readonly class SignDocumentHandler extends Handler
{
    public function handle(SignDocumentAction $action): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->loadXML($action->document);

        $ids = [];
        $digest1 = base64_encode(hash('sha256', $dom->C14N(), true));

        $signature = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:Signature');
        $signature->setAttribute('Id', $ids['signature'] = Str::guid());

        $dom->firstChild?->appendChild($signature);

        $signedInfo = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:SignedInfo');
        $signedInfo->setAttribute('Id', Str::guid());

        $signature->appendChild($signedInfo);

        $canonicalizationMethod = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:CanonicalizationMethod');
        $canonicalizationMethod->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');

        $signedInfo->appendChild($canonicalizationMethod);

        $signatureMethod = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:SignatureMethod');
        $signatureMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256');

        $signedInfo->appendChild($signatureMethod);

        $reference1 = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:Reference');
        $reference1->setAttribute('Id', $ids['reference1'] = Str::guid());
        $reference1->setAttribute('URI', '');

        $signedInfo->appendChild($reference1);

        $transforms = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:Transforms');

        $reference1->appendChild($transforms);

        $transform = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:Transform');
        $transform->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');

        $transforms->appendChild($transform);

        $digestMethod = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:DigestMethod');
        $digestMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmlenc#sha256');

        $reference1->appendChild($digestMethod);

        $digestValue = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:DigestValue', $digest1);

        $reference1->appendChild($digestValue);

        $reference2 = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:Reference');
        $reference2->setAttribute('Id', Str::guid());
        $reference2->setAttribute('Type', 'http://uri.etsi.org/01903#SignedProperties');

        $signedInfo->appendChild($reference2);

        $digestMethod2 = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:DigestMethod');
        $digestMethod2->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmlenc#sha256');

        $reference2->appendChild($digestMethod2);

        $signatureValue = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:SignatureValue');
        $signatureValue->setAttribute('Id', Str::guid());

        $signature->appendChild($signatureValue);

        $keyInfo = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:KeyInfo');

        $signature->appendChild($keyInfo);

        $x509data = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:X509Data');

        $keyInfo->appendChild($x509data);

        $x509Certificate = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:X509Certificate', $action->certificate->raw);

        $x509data->appendChild($x509Certificate);

        $object = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:Object');

        $signature->appendChild($object);

        $qualifyingProperties = $dom->createElementNS((string) XmlNamespace::Xades->value, 'xades:QualifyingProperties');
        $qualifyingProperties->setAttribute('Id', Str::guid());
        $qualifyingProperties->setAttribute('Target', "#" . $ids['signature']);

        $object->appendChild($qualifyingProperties);

        $signedProperties = $dom->createElementNS((string) XmlNamespace::Xades->value, 'xades:SignedProperties');
        $signedProperties->setAttribute('Id', $ids['signed_properties'] = Str::guid());

        $qualifyingProperties->appendChild($signedProperties);

        $reference2->setAttribute('URI', "#" . $ids['signed_properties']);

        $signedSignatureProperties = $dom->createElementNS((string) XmlNamespace::Xades->value, 'xades:SignedSignatureProperties');

        $signedProperties->appendChild($signedSignatureProperties);

        $signatureTime = $dom->createElementNS((string) XmlNamespace::Xades->value, 'xades:SigningTime', new DateTimeImmutable()->format('Y-m-d\TH:i:sp'));

        $signedSignatureProperties->appendChild($signatureTime);

        $signingCertificate = $dom->createElementNS((string) XmlNamespace::Xades->value, 'xades:SigningCertificate');

        $signedSignatureProperties->appendChild($signingCertificate);

        $xadesCert = $dom->createElementNS((string) XmlNamespace::Xades->value, 'xades:Cert');

        $signingCertificate->appendChild($xadesCert);

        $xadesCertDigest = $dom->createElementNS((string) XmlNamespace::Xades->value, 'xades:CertDigest');

        $xadesCert->appendChild($xadesCertDigest);

        $digestMethod3 = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:DigestMethod');
        $digestMethod3->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmlenc#sha256');

        $xadesCertDigest->appendChild($digestMethod3);

        $digestValue = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:DigestValue', $action->certificate->getFingerPrint());

        $xadesCertDigest->appendChild($digestValue);

        $xadesIssuerSerial = $dom->createElementNS((string) XmlNamespace::Xades->value, 'xades:IssuerSerial');

        $xadesCert->appendChild($xadesIssuerSerial);

        $x509IssuerName = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:X509IssuerName', $action->certificate->getIssuer());

        $xadesIssuerSerial->appendChild($x509IssuerName);

        $x509SerialNumber = $dom->createElementNS((string) XmlNamespace::Ds->value, 'ds:X509SerialNumber', $action->certificate->getSerialNumber());

        $xadesIssuerSerial->appendChild($x509SerialNumber);

        $xmlDigest = base64_encode(hash('sha256', $signedProperties->C14N(), true));

        $digestValue = $dom->createElementNS((string) XmlNamespace::Ds->value, 'DigestValue', $xmlDigest);
        $digestValue->setAttribute('xmlns', 'http://www.w3.org/2000/09/xmldsig#');

        $reference2->appendChild($digestValue);

        $actualDigest = '';

        $sign = openssl_sign(
            $signedInfo->C14N(),
            $actualDigest,
            $action->certificate->privateKey,
            'sha256WithRSAEncryption'
        );

        if ($sign === false) {
            throw new RuntimeException('Unable to sign document');
        }

        /** @var string $actualDigest */
        $signatureValue->textContent = base64_encode($actualDigest);

        return $dom->saveXML() ?: throw new RuntimeException('Unable to serialize to XML');
    }
}
