
![1920x810](https://github.com/user-attachments/assets/7db28b6a-80fc-4651-9d07-f04aad6ec8c7)

# KSEF PHP Client

> **This package is not production ready yet!**

PHP API client that allows you to interact with the [KSEF API](https://api-test.ksef.mf.gov.pl/docs/v2/index.html) Krajowy System e-Faktur

Main features:

- Support for authorization using qualified certificates, KSeF certificates, KSeF tokens, and trusted ePUAP signatures (manual mode)
- Support for signatures with both RSA and EC keys
- Support for async batch send multiple invoices
- Logical FA (3) and FA_RR (1) invoices structure mapped to DTOs and ValueObjects
- Automatic access token refresh
- Automatic XML document validation based on XSD schemas
- CSR (Certificate Signing Request) handling
- KSeF exception handling
- PDF invoice nad UPO generation
- QR codes generation

|  KSEF Version  |     Branch     | Release Version |
|:--------------:|:--------------:|:---------------:|
|       2.0      |      main      |      ^1.0       |
|       1.0      |      0.2.x     |      0.2.*      |

## Table of Contents

- [Get Started](#get-started)
    - [Client configuration](#client-configuration)
    - [Auto mapping](#auto-mapping)
        - [Deserialization](#deserialization)
        - [Valinor Cache](#valinor-cache)
- [Authorization](#authorization)
    - [Auto authorization via KSEF Token](#auto-authorization-via-ksef-token)
    - [Auto authorization via certificate .p12](#auto-authorization-via-certificate-p12)
    - [Manual authorization](#manual-authorization)
- [Resources](#resources)
    - [Auth](#auth)
        - [Auth Challenge](#auth-challenge)
        - [Auth Xades Signature](#auth-xades-signature)
        - [Auth Status](#auth-status)
        - [Auth Token](#auth-token)
            - [Auth Token Redeem](#auth-token-redeem)
            - [Auth Token Refresh](#auth-token-refresh)
        - [Auth Sessions](#auth-sessions)
            - [Auth Session List](#auth-sessions-list)
            - [Auth Session Revoke Current](#auth-sessions-revoke-current)
            - [Auth Session Revoke](#auth-sessions-revoke)
    - [Limits](#limits)
        - [Limits Context](#limits-context)
        - [Limits Subject](#limits-subject)
    - [Rate Limits](#rate-limits)
    - [Security](#security)
        - [Security Public Key Certificates](#security-public-key-certificates)
    - [Sessions](#sessions)
        - [Sessions List](#sessions-list)
        - [Sessions Invoices](#sessions-invoices)
            - [Sessions Invoices List](#sessions-invoices-list)
            - [Sessions Invoices Failed](#sessions-invoices-failed)
            - [Sessions Invoices Upo](#sessions-invoices-upo)
            - [Sessions Invoices Ksef Upo](#sessions-invoices-ksef-upo)
            - [Sessions Invoices Status](#sessions-invoices-status)
        - [Sessions Online](#sessions-online)
            - [Sessions Online Open](#sessions-online-open)
            - [Sessions Online Close](#sessions-online-close)
            - [Sessions Online Send invoices](#sessions-online-send-invoices)
        - [Sessions Batch](#sessions-batch)
            - [Sessions Batch Open (and send multiple invoices)](#sessions-batch-open-and-send-multiple-invoices)
            - [Sessions Batch Close](#sessions-batch-close)
        - [Sessions Status](#sessions-status)
        - [Sessions Upo](#sessions-upo)
    - [Invoices](#invoices)
        - [Invoices Download](#invoices-download)
        - [Invoices Query](#invoices-query)
            - [Invoices Query Metadata](#invoices-query-metadata)
        - [Invoices Exports](#invoices-exports)
            - [Invoices Exports Init](#invoices-exports-init)
            - [Invoices Exposts Status](#invoices-exports-status)
    - [Permissions](#permissions)
        - [Permissions Persons](#permissions-persons)
            - [Permissions Persons Grants](#permissions-persons-grants)
        - [Permissions Entities](#permissions-entities)
            - [Permissions Entities Grants](#permissions-entities-grants)
        - [Permissions Authorizations](#permissions-authorizations)
            - [Permissions Authorizations Grants](#permissions-authorizations-grants)
            - [Permissions Authorizations Grants Revoke](#permissions-authorizations-grants-revoke)
        - [Permissions Indirect](#permissions-indirect)
            - [Permissions Indirect Grants](#permissions-indirect-grants)
        - [Permissions Subunits](#permissions-subunits)
            - [Permissions Subunits Grants](#permissions-subunits-grants)
        - [Permissions EuEntities](#permissions-euentities)
            - [Permissions EuEntities Administration](#permissions-euentities-administration)
                - [Permissions EuEntities Administration Grants](#permissions-euentities-administration-grants)
            - [Permissions EuEntities Grants](#permissions-euentities-grants)
        - [Permissions Common](#permissions-common)
            - [Permissions Common Grants Revoke](#permissions-common-grants-revoke)
        - [Permissions Query](#permissions-query)
            - [Permissions Query Authorizations](#permissions-query-authorizations)
                - [Permissions Query Authorizations Grants](#permissions-query-authorizations-grants)        
            - [Permissions Query Entities](#permissions-query-entities)
                - [Permissions Query Entities Grants](#permissions-query-entities-grants)
                - [Permissions Query Entities Roles](#permissions-query-entities-roles)
            - [Permissions Query EuEntities](#permissions-query-euentities)
                - [Permissions Query EuEntities Grants](#permissions-query-euentities-grants)
            - [Permissions Query Personal](#permissions-query-personal)
                - [Permissions Query Personal Grants](#permissions-query-personal-grants)
            - [Permissions Query Persons](#permissions-query-persons)
                - [Permissions Query Persons Grants](#permissions-query-persons-grants)
            - [Permissions Query Subunits](#permissions-query-subunits)
                - [Permissions Query Subunits Grants](#permissions-query-subunits-grants)
            - [Permissions Query SubordinateEntities](#permissions-query-subordinateentities)
                - [Permissions Query SubordinateEntities Roles](#permissions-query-subordinateentities-roles)
        - [Permissions Operations](#permissions-operations)
            - [Permissions Operations Status](#permissions-operations-status)
        - [Permissions Attachments](#permissions-attachments)
            - [Permissions Attachments Status](#permissions-attachments-status)            
    - [Certificates](#certificates)
        - [Certificates Limits](#certificates-limits)
        - [Certificates Enrollments](#certificates-enrollments)
            - [Certificates Enrollments Data](#certificates-enrollments-data)
            - [Certificates Enrollments Send](#certificates-enrollments-send)
            - [Certificates Enrollments Status](#certificates-enrollments-status)
        - [Certificates Retrieve](#certificates-retrieve)
        - [Certificates Revoke](#certificates-revoke)
        - [Certificates Query](#certificates-query)
    - [Tokens](#tokens)
        - [Tokens Create](#tokens-create)
        - [Tokens List](#tokens-list)
        - [Tokens Status](#tokens-status)
        - [Tokens Revoke](#tokens-revoke)
    - [Peppol](#peppol)
        - [Peppol Query](#peppol-query)
    - [Testdata](#testdata)
        - [Testdata Attachment](#testdata-attachment)
          - [Testdata Attachment Approve](#testdata-attachment-approve)
          - [TestData Attachment Revoke](#testdata-attachment-revoke)
        - [Testdata Person](#testdata-person)
            - [Testdata Person Create](#testdata-person-create)
            - [Testdata Person Remove](#testdata-person-remove)
        - [Testdata Context](#testdata-context)
            - [Testdata Context Block](#testdata-context-block)
            - [Testdata Context Unblock](#testdata-context-unblock)
        - [Testdata Permissions](#testdata-permissions)
            - [Testdata Permissions Grants](#testdata-permissions-grants)
            - [Testdata Permissions Revoke](#testdata-permissions-revoke)
        - [Testdata Limits](#testdata-limits)
            - [Testdata Limits Context](#testdata-limits-context)
                - [Testdata Limits Context Session](#testdata-limits-context-session)
                    - [Testdata Limits Context Session Limits](#testdata-limits-context-session-limits)
                    - [Testdata Limits Context Session Reset](#testdata-limits-context-session-reset)
            - [Testdata Limits Subject](#testdata-limits-subject)
                - [Testdata Limits Subject Certificate](#testdata-limits-subject-certificate)
                    - [Testdata Limits Subject Certificate Limits](#testdata-limits-subject-certificate-limits)
                    - [Testdata Limits Subject Certificate Reset](#testdata-limits-subject-certificate-reset)
        - [Testdata Rate Limits](#testdata-rate-limits)
            - [Testdata Rate Limits Limits](#testdata-rate-limits-limits)
            - [Testdata Rate Limits Reset](#testdata-rate-limits-reset)
            - [Testdata Rate Limits Production](#testdata-rate-limits-production)
    - [Latarnia](#latarnia)
        - [Latarnia Status](#latarnia-status)
        - [Latarnia Messages](#latarnia-messages)

- [Examples](#examples)
    - [Integration with a frontend application using certificate-based authentication](#integration-with-a-frontend-application-using-certificate-based-authentication)
    - [Conversion of the KSEF certificate and private key from MCU to a .p12 file](#conversion-of-the-ksef-certificate-and-private-key-from-mcu-to-a-p12-file)
    - [Generate a KSEF certificate and convert to .p12 file](#generate-a-ksef-certificate-and-convert-to-a-p12-file)
    - [Send an invoice, check for UPO and generate QR code](#send-an-invoice-check-for-upo-and-generate-qr-code)
    - [Generate PDF for the invoice and the UPO file](#generate-pdf-for-the-invoice-and-the-upo-file)
    - [Generate PDF for the transaction confirmation with both QR codes](#generate-pdf-for-the-transaction-confirmation-with-both-qr-codes)
    - [Batch async send multiple invoices and check for UPO](#batch-async-send-multiple-invoices-and-check-for-upo)
    - [Create an offline invoice and generate both QR codes](#create-an-offline-invoice-and-generate-both-qr-codes)
    - [Generate PDF for the offline invoice file with both QR codes](#generate-pdf-for-the-offline-invoice-file-with-both-qr-codes)
    - [Download and decrypt invoices using the encryption key](#download-and-decrypt-invoices-using-the-encryption-key)
- [Testing](#testing)
- [Roadmap](#roadmap)
- [Special thanks](#special-thanks)

## Get Started

> **Requires [PHP 8.1+](https://www.php.net/releases/)**

First, install `ksef-php-client` via the [Composer](https://getcomposer.org/) package manager:

```bash
composer require n1ebieski/ksef-php-client
```

Ensure that the `php-http/discovery` composer plugin is allowed to run or install a client manually if your project does not already have a PSR-18 client integrated.

```bash
composer require guzzlehttp/guzzle
```

### Client configuration

```php
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\Factories\ValinorCacheFactory;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;

$client = (new ClientBuilder())
    ->withMode(Mode::Production) // Choice between: Test, Demo, Production
    ->withApiUrl($_ENV['KSEF_API_URL']) // Optional, default is set by Mode selection
    ->withLatarniaApiUrl($_ENV['KSEF_LATARNIA_API_URL']) // Optional, default is set by Mode selection
    ->withHttpClient(new \GuzzleHttp\Client(...)) // Optional PSR-18 implementation, default is set by Psr18ClientDiscovery::find()
    ->withCache(new \Symfony\Component\Cache\Psr16Cache(...), $_ENV['CACHE_TTL']) // Optional PSR-16 implementation, default is null
    ->withValinorCache(ValinorCacheFactory::make()) // Optional CuyZ\Valinor\Cache\Cache implementation for caching auto-mapping DTO, default is null, more information: https://valinor-php.dev/2.3/other/performance-and-caching/
    ->withLogger(new \Monolog\Logger(...)) // Optional PSR-3 implementation, default is set by PsrDiscovery\Discover::log()
    ->withLogPath($_ENV['PATH_TO_LOG_FILE'], $_ENV['LOG_LEVEL']) // Optional, level: null disables logging
    ->withExceptionHandler(new \ExceptionHandler(...)) // Optional N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface implmentation
    ->withAccessToken($_ENV['ACCESS_TOKEN'], $_ENV['VALID_UNTIL']) // Optional, if present, auto authorization is skipped
    ->withRefreshToken($_ENV['REFRESH_TOKEN'], $_ENV['VALID_UNTIL']) // Optional, if present, auto refresh access token is enabled
    ->withKsefToken($_ENV['KSEF_TOKEN']) // Required for API Token authorization. Optional otherwise
    ->withCertificate($_ENV['CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE']) // Required .p12 contents for Certificate authorization. Optional otherwise
    ->withCertificatePath($_ENV['PATH_TO_CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE']) // Required path to .p12 file for Certificate authorization. Optional otherwise
    ->withVerifyCertificateChain(true) // Optional. Explanation https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Uzyskiwanie-dostepu/paths/~1auth~1xades-signature/post
    ->withEncryptionKey(EncryptionKeyFactory::makeRandom()) // Required for invoice resources. Remember to save this value!
    ->withIdentifier('NIP_NUMBER') // Required for authorization. Optional otherwise
    ->withAsyncMaxConcurrency(8) // Optional. Maximum concurrent send operations during asynchronous sending
    ->withValidateXml(true) // Optional. XML document validation based on XSD schemas
    ->withRetryTiming(10, 120) // Optional. Authorization status retry timing: backoff in seconds and max wait time in seconds
    ->build();
```

### Auto mapping

Each resource supports mapping through both an array and a DTO, for example:

```php
use N1ebieski\KSEFClient\Requests\Auth\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\ValueObjects\ReferenceNumber;

$authorisationStatusResponse = $client->auth()->status(new StatusRequest(
    referenceNumber: ReferenceNumber::from('20250508-EE-B395BBC9CD-A7DB4E6095-BD')
))->object();
```

or:

```php
$authorisationStatusResponse = $client->auth()->status([
    'referenceNumber' => '20250508-EE-B395BBC9CD-A7DB4E6095-BD'
])->object();
```

#### Deserialization

DTOs that implement `N1ebieski\KSEFClient\Contracts\XmlDeserializableInterface` can be deserialized directly from XML.

Example:

```php
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;

$faktura = Faktura::fromXml('<Faktura> ... </Faktura>');
```

#### Valinor Cache

For best performance, it is recommended to use caching:

```php
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Factories\ValinorCacheFactory;

$client = (new ClientBuilder())
    ->withValinorCache(ValinorCacheFactory::make()) // Or other CuyZ\Valinor\Cache\Cache implementation
```

or directly for a DTO:

```php
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;

$faktura = Faktura::from([...], ValinorCacheFactory::make());

$deserialized = Faktura::fromXml($faktura->toXml(), ValinorCacheFactory::make());
```

> [!IMPORTANT]
> If you are using caching, remember to clear the cache after updating this package!

More information: https://valinor-php.dev/2.3/other/performance-and-caching/

## Authorization

<details open>
    <summary>
        <h3>Auto authorization via KSEF Token</h3>
    </summary>

```php
use N1ebieski\KSEFClient\ClientBuilder;

$client = (new ClientBuilder())
    ->withKsefToken($_ENV['KSEF_KEY'])
    ->withIdentifier('NIP_NUMBER')
    ->build();

// Do something with the available resources
```
</details>

<details>
    <summary>
        <h3>Auto authorization via certificate .p12</h3>
    </summary>

```php
use N1ebieski\KSEFClient\ClientBuilder;

$client = (new ClientBuilder())
    ->withCertificatePath($_ENV['PATH_TO_CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE'])
    ->withIdentifier('NIP_NUMBER')
    ->build();

// Do something with the available resources
```

or:

```php
use N1ebieski\KSEFClient\ClientBuilder;

$client = (new ClientBuilder())
    ->withCertificate($_ENV['CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE'])
    ->withIdentifier('NIP_NUMBER')
    ->build();

// Do something with the available resources
```
</details>

<details>
    <summary>
        <h3>Manual authorization</h3>
    </summary>

```php
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Requests\Auth\DTOs\XadesSignature;
use N1ebieski\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureXmlRequest;

$client = (new ClientBuilder())->build();

$nip = 'NIP_NUMBER';

$authorisationChallengeResponse = $client->auth()->challenge()->object();

$xml = XadesSignature::from([
    'challenge' => $authorisationChallengeResponse->challenge,
    'contextIdentifierGroup' => [
        'identifierGroup' => [
            'nip' => $nip
        ]
    ],
    'subjectIdentifierType' => 'certificateSubject'
])->toXml();

$signedXml = 'SIGNED_XML_DOCUMENT'; // Sign a xml document via Szafir, ePUAP etc.

$authorisationAccessResponse = $client->auth()->xadesSignature(
    new XadesSignatureXmlRequest($signedXml)
)->object();

$client = $client->withAccessToken($authorisationAccessResponse->authenticationToken->token);

$authorisationStatusResponse = Utility::retry(function () use ($client, $authorisationAccessResponse) {
    $authorisationStatusResponse = $client->auth()->status([
        'referenceNumber' => $authorisationAccessResponse->referenceNumber
    ])->object();

    if ($authorisationStatusResponse->status->code === 200) {
        return $authorisationStatusResponse;
    }

    if ($authorisationStatusResponse->status->code >= 400) {
        throw new RuntimeException(
            $authorisationStatusResponse->status->description,
            $authorisationStatusResponse->status->code
        );
    }
});

$authorisationTokenResponse = $client->auth()->token()->redeem()->object();

$client = $client
    ->withAccessToken(
        token: $authorisationTokenResponse->accessToken->token, 
        validUntil: $authorisationTokenResponse->accessToken->validUntil
    )
    ->withRefreshToken(
        token: $authorisationTokenResponse->refreshToken->token,
        validUntil: $authorisationTokenResponse->refreshToken->validUntil
    );

// Do something with the available resources
```
</details>

## Resources

### Auth

<details>
    <summary>
        <h4>Auth Challenge</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Uzyskiwanie-dostepu/paths/~1auth~1challenge/post

```php
$response = $client->auth()->challenge()->object();
```
</details>

<details>
    <summary>
        <h4>Auth Xades Signature</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Uzyskiwanie-dostepu/paths/~1auth~1xades-signature/post

```php
use N1ebieski\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureRequest;

$response = $client->auth()->xadesSignature(
    new XadesSignatureRequest(...)
)->object();
```

or:

```php
use N1ebieski\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureXmlRequest;

$response = $client->auth()->xadesSignature(
    new XadesSignatureXmlRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h4>Auth Status</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Uzyskiwanie-dostepu/paths/~1auth~1%7BreferenceNumber%7D/get

```php
use N1ebieski\KSEFClient\Requests\Auth\Status\StatusRequest;

$response = $client->auth()->status(
    new StatusRequest(...)
)->object();
```
</details>

#### Auth Token

<details>
    <summary>
        <h5>Auth Token Redeem</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Uzyskiwanie-dostepu/paths/~1auth~1token~1redeem/post

```php
$response = $client->auth()->token()->redeem()->object();
```
</details>

<details>
    <summary>
        <h5>Auth Token Refresh</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Uzyskiwanie-dostepu/paths/~1auth~1token~1refresh/post

```php
$response = $client->auth()->token()->refresh()->object();
```
</details>

#### Auth Sessions

<details>
    <summary>
        <h5>Auth Sessions list</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Aktywne-sesje/paths/~1auth~1sessions/get

```php
use N1ebieski\KSEFClient\Requests\Auth\Sessions\List\ListRequest;

$response = $client->auth()->sessions()->list(
    new ListRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h5>Auth Sessions revoke current</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Aktywne-sesje/paths/~1auth~1sessions~1current/delete

```php
$response = $client->auth()->sessions()->revokeCurrent()->status();
```
</details>

<details>
    <summary>
        <h5>Auth Sessions revoke</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Aktywne-sesje/paths/~1auth~1sessions~1%7BreferenceNumber%7D/delete

```php
use N1ebieski\KSEFClient\Requests\Auth\Sessions\Revoke\RevokeRequest;

$response = $client->auth()->sessions()->revoke(
    new RevokeRequest(...)
)->status();
```
</details>

### Limits

<details>
    <summary>
        <h4>Limits Context</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1limits~1context/get

```php
use N1ebieski\KSEFClient\Requests\Limits\Context\ContextRequest;

$response = $client->limits()->context(
    new ContextRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h4>Limits Subject</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1limits~1subject/get

```php
use N1ebieski\KSEFClient\Requests\Limits\Subject\SubjectRequest;

$response = $client->limits()->subject(
    new SubjectRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h3>Rate Limits</h3>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1rate-limits/get

```php
$response = $client->rateLimits()->object();
```
</details>

### Security

<details>
    <summary>
        <h4>Security Public Key Certificates</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Certyfikaty-klucza-publicznego/paths/~1security~1public-key-certificates/get

```php
$response = $client->security()->publicKeyCertificates()->object();
```
</details>

### Sessions

<details>
    <summary>
        <h4>Sessions List</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Status-wysylki-i-UPO/paths/~1sessions/get

```php
use N1ebieski\KSEFClient\Requests\Sessions\List\ListRequest;

$response = $client->sessions()->list(
    new ListRequest(...)
)->object();
```
</details>

#### Sessions Invoices

<details>
    <summary>
        <h5>Sessions Invoices List</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Status-wysylki-i-UPO/paths/~1sessions~1%7BreferenceNumber%7D~1invoices/get

```php
use N1ebieski\KSEFClient\Requests\Sessions\Invoices\List\ListRequest;

$response = $client->sessions()->invoices()->list(
    new ListRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h5>Sessions Invoices Failed</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Status-wysylki-i-UPO/paths/~1sessions~1%7BreferenceNumber%7D~1invoices~1failed/get

```php
use N1ebieski\KSEFClient\Requests\Sessions\Invoices\Failed\FailedRequest;

$response = $client->sessions()->invoices()->failed(
    new FailedRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h5>Sessions Invoices Upo</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Status-wysylki-i-UPO/paths/~1sessions~1%7BreferenceNumber%7D~1invoices~1%7BinvoiceReferenceNumber%7D~1upo/get

```php
use N1ebieski\KSEFClient\Requests\Sessions\Invoices\Upo\UpoRequest;

$response = $client->sessions()->invoices()->upo(
    new UpoRequest(...)
)->body();
```
</details>

<details>
    <summary>
        <h5>Sessions Invoices Ksef Upo</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Status-wysylki-i-UPO/paths/~1sessions~1%7BreferenceNumber%7D~1invoices~1ksef~1%7BksefNumber%7D~1upo/get

```php
use N1ebieski\KSEFClient\Requests\Sessions\Invoices\KsefUpo\KsefUpoRequest;

$response = $client->sessions()->invoices()->ksefUpo(
    new KsefUpoRequest(...)
)->body();
```
</details>

<details>
    <summary>
        <h5>Sessions Invoices Status</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Status-wysylki-i-UPO/paths/~1sessions~1%7BreferenceNumber%7D~1invoices~1%7BinvoiceReferenceNumber%7D/get

```php
use N1ebieski\KSEFClient\Requests\Sessions\Invoices\Status\StatusRequest;

$response = $client->sessions()->invoices()->status(
    new StatusRequest(...)
)->object();
```
</details>

#### Sessions Online

<details>
    <summary>
        <h5>Sessions Online Open</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wysylka-interaktywna/paths/~1sessions~1online/post

```php
use N1ebieski\KSEFClient\Requests\Sessions\Online\Open\OpenRequest;

$response = $client->sessions()->online()->open(
    new OpenRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h5>Sessions Online Close</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wysylka-interaktywna/paths/~1sessions~1online~1%7BreferenceNumber%7D~1close/post

```php
use N1ebieski\KSEFClient\Requests\Sessions\Online\Close\CloseRequest;

$response = $client->sessions()->online()->close(
    new CloseRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h5>Sessions Online Send invoices</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wysylka-interaktywna/paths/~1sessions~1online~1%7BreferenceNumber%7D~1invoices/post

for DTO invoice:

```php
use N1ebieski\KSEFClient\Requests\Sessions\Online\Send\SendRequest;

$response = $client->sessions()->online()->send(
    new SendRequest(...)
)->object();
```

for XML invoice:

```php
use N1ebieski\KSEFClient\Requests\Sessions\Online\Send\SendXmlRequest;

$response = $client->sessions()->online()->send(
    new SendXmlRequest(...)
)->object();
```
</details>

#### Sessions Batch

<details>
    <summary>
        <h5>Sessions Batch Open (and send multiple invoices)</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wysylka-wsadowa/paths/~1sessions~1batch/post

for DTOs invoices:

```php
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendRequest;

$response = $client->sessions()->batch()->openAndSend(
    new OpenAndSendRequest(...)
)->object();
```

for XMLs invoices:

```php
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendXmlRequest;

$response = $client->sessions()->batch()->openAndSend(
    new OpenAndSendXmlRequest(...)
)->object();
```

for ZIP invoices:

```php
use N1ebieski\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendZipRequest;

$response = $client->sessions()->batch()->openAndSend(
    new OpenAndSendZipRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h5>Sessions Batch Close</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wysylka-wsadowa/paths/~1sessions~1batch~1%7BreferenceNumber%7D~1close/post

```php
use N1ebieski\KSEFClient\Requests\Sessions\Batch\Close\CloseRequest;

$response = $client->sessions()->batch()->close(
    new CloseRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h4>Sessions Status</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Status-wysylki-i-UPO/paths/~1sessions~1%7BreferenceNumber%7D/get

```php
use N1ebieski\KSEFClient\Requests\Sessions\Status\StatusRequest;

$response = $client->sessions()->status(
    new StatusRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h4>Sessions Upo</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Status-wysylki-i-UPO/paths/~1sessions~1%7BreferenceNumber%7D~1upo~1%7BupoReferenceNumber%7D/get

```php
use N1ebieski\KSEFClient\Requests\Sessions\Upo\UpoRequest;

$response = $client->sessions()->upo(
    new UpoRequest(...)
)->body();
```
</details>

### Invoices

<details>
    <summary>
        <h4>Invoices Download</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Pobieranie-faktur/paths/~1invoices~1ksef~1%7BksefNumber%7D/get

```php
use N1ebieski\KSEFClient\Requests\Invoices\Download\DownloadRequest;

$response = $client->invoices()->download(
    new DownloadRequest(...)
)->body();
```
</details>

#### Invoices Query

<details>
    <summary>
        <h5>Invoices Query Metadata</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Pobieranie-faktur/paths/~1invoices~1query~1metadata/post

```php
use N1ebieski\KSEFClient\Requests\Invoices\Query\Metadata\MetadataRequest;

$response = $client->invoices()->query()->metadata(
    new MetadataRequest(...)
)->object();
```
</details>

#### Invoices Exports

<details>
    <summary>
        <h5>Invoices Exports Init</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Pobieranie-faktur/paths/~1invoices~1exports/post

```php
use N1ebieski\KSEFClient\Requests\Invoices\Exports\Init\InitRequest;

$response = $client->invoices()->exports()->init(
    new InitRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h5>Invoices Exports Status</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Pobieranie-faktur/paths/~1invoices~1exports~1%7BoperationReferenceNumber%7D/get

```php
use N1ebieski\KSEFClient\Requests\Invoices\Exports\Status\StatusRequest;

$response = $client->invoices()->exports()->status(
    new StatusRequest(...)
)->object();
```
</details>

### Permissions

#### Permissions Persons

<details>
    <summary>
        <h5>Permissions Persons Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Nadawanie-uprawnien/paths/~1permissions~1persons~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Persons\Grants\GrantsRequest;

$response = $client->permissions()->persons()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

#### Permissions Entities

<details>
    <summary>
        <h5>Permissions Entities Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Nadawanie-uprawnien/paths/~1permissions~1entities~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Entities\Grants\GrantsRequest;

$response = $client->permissions()->entities()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

#### Permissions Authorizations

<details>
    <summary>
        <h5>Permissions Authorizations Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Nadawanie-uprawnien/paths/~1permissions~1authorizations~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Authorizations\Grants\GrantsRequest;

$response = $client->permissions()->authorizations()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h5>Permissions Authorizations Grants Revoke</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Odbieranie-uprawnien/paths/~1permissions~1authorizations~1grants~1%7BpermissionId%7D/delete

```php
use N1ebieski\KSEFClient\Requests\Permissions\Authorizations\Revoke\RevokeRequest;

$response = $client->permissions()->authorizations()->revoke(
    new RevokeRequest(...)
)->object();
```
</details>

#### Permissions Indirect

<details>
    <summary>
        <h5>Permissions Indirect Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Nadawanie-uprawnien/paths/~1permissions~1indirect~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Indirect\Grants\GrantsRequest;

$response = $client->permissions()->indirect()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

#### Permissions Subunits

<details>
    <summary>
        <h5>Permissions Subunits Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Nadawanie-uprawnien/paths/~1permissions~1subunits~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Subunits\Grants\GrantsRequest;

$response = $client->permissions()->subunits()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

#### Permissions EuEntities

<details>
    <summary>
        <h5>Permissions EuEntities Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Nadawanie-uprawnien/paths/~1permissions~1eu-entities~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\EuEntities\Grants\GrantsRequest;

$response = $client->permissions()->euEntities()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

##### Permissions EuEntities Administration

<details>
    <summary>
        <h5>Permissions EuEntities Administration Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Nadawanie-uprawnien/paths/~1permissions~1eu-entities~1administration~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\EuEntities\Administration\Grants\GrantsRequest;

$response = $client->permissions()->euEntities()->administration()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

#### Permissions Common

<details>
    <summary>
        <h5>Permissions Common Grants Revoke</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Odbieranie-uprawnien/paths/~1permissions~1common~1grants~1%7BpermissionId%7D/delete

```php
use N1ebieski\KSEFClient\Requests\Permissions\Common\Revoke\RevokeRequest;

$response = $client->permissions()->common()->revoke(
    new RevokeRequest(...)
)->object();
```
</details>

#### Permissions Query

##### Permissions Query Authorizations

<details>
    <summary>
        <h5>Permissions Query Authorizations Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wyszukiwanie-nadanych-uprawnien/paths/~1permissions~1query~1authorizations~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Query\Authorizations\Grants\GrantsRequest;

$response = $client->permissions()->query()->authorizations()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

##### Permissions Query Entities

<details>
    <summary>
        <h5>Permissions Query Entities Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wyszukiwanie-nadanych-uprawnien/paths/~1permissions~1query~1entities~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Query\Entities\Grants\GrantsRequest;

$response = $client->permissions()->query()->entities()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h5>Permissions Query Entities Roles</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wyszukiwanie-nadanych-uprawnien/paths/~1permissions~1query~1entities~1roles/get

```php
use N1ebieski\KSEFClient\Requests\Permissions\Query\Entities\Roles\RolesRequest;

$response = $client->permissions()->query()->entities()->roles(
    new RolesRequest(...)
)->object();
```
</details>

##### Permissions Query EuEntities

<details>
    <summary>
        <h5>Permissions Query EuEntities Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wyszukiwanie-nadanych-uprawnien/paths/~1permissions~1query~1eu-entities~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Query\EuEntities\Grants\GrantsRequest;

$response = $client->permissions()->query()->euEntities()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

##### Permissions Query Personal

<details>
    <summary>
        <h5>Permissions Query Personal Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wyszukiwanie-nadanych-uprawnien/paths/~1permissions~1query~1personal~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Query\Personal\Grants\GrantsRequest;

$response = $client->permissions()->query()->personal()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

##### Permissions Query Persons

<details>
    <summary>
        <h5>Permissions Query Persons Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wyszukiwanie-nadanych-uprawnien/paths/~1permissions~1query~1persons~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Query\Persons\Grants\GrantsRequest;

$response = $client->permissions()->query()->persons()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

##### Permissions Query Subunits

<details>
    <summary>
        <h5>Permissions Query Subunits Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wyszukiwanie-nadanych-uprawnien/paths/~1permissions~1query~1subunits~1grants/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Query\Subunits\Grants\GrantsRequest;

$response = $client->permissions()->query()->subunits()->grants(
    new GrantsRequest(...)
)->object();
```
</details>

##### Permissions Query SubordinateEntities

<details>
    <summary>
        <h5>Permissions Query SubordinateEntities Roles</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Wyszukiwanie-nadanych-uprawnien/paths/~1permissions~1query~1subordinate-entities~1roles/post

```php
use N1ebieski\KSEFClient\Requests\Permissions\Query\SubordinateEntities\Roles\RolesRequest;

$response = $client->permissions()->query()->subordinateEntities()->roles(
    new RolesRequest(...)
)->object();
```
</details>

#### Permissions Operations

<details>
    <summary>
        <h5>Permissions Operations Status</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Operacje/paths/~1permissions~1operations~1%7BreferenceNumber%7D/get

```php
use N1ebieski\KSEFClient\Requests\Permissions\Operations\Status\StatusRequest;

$response = $client->permissions()->operations()->status(
    new StatusRequest(...)
)->object();
```
</details>

#### Permissions Attachments

<details>
    <summary>
        <h5>Permissions Attachments Status</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Operacje/paths/~1permissions~1attachments~1status/get

```php
use N1ebieski\KSEFClient\Requests\Permissions\Attachments\Status\StatusRequest;

$response = $client->permissions()->attachments()->status(
    new StatusRequest(...)
)->object();
```
</details>

### Certificates

<details>
    <summary>
        <h4>Certificates Limits</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Certyfikaty/paths/~1certificates~1limits/get

```php
$response = $client->certificates()->limits()->object();
```
</details>

#### Certificates Enrollments

<details>
    <summary>
        <h5>Certificates Enrollments Data</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Certyfikaty/paths/~1certificates~1enrollments~1data/get

```php
$response = $client->certificates()->enrollments()->data()->object();
```
</details>

<details>
    <summary>
        <h5>Certificates Enrollments Send</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Certyfikaty/paths/~1certificates~1enrollments/post

```php
use N1ebieski\KSEFClient\Requests\Certificates\Enrollments\Send\SendRequest;

$response = $client->certificates()->enrollments()->send(
    new SendRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h5>Certificates Enrollments Status</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Certyfikaty/paths/~1certificates~1enrollments~1%7BreferenceNumber%7D/get

```php
use N1ebieski\KSEFClient\Requests\Certificates\Enrollments\Status\StatusRequest;

$response = $client->certificates()->enrollments()->status(
    new StatusRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h4>Certificates Retrieve</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Certyfikaty/paths/~1certificates~1retrieve/post

```php
use N1ebieski\KSEFClient\Requests\Certificates\Retrieve\RetrieveRequest;

$response = $client->certificates()->retrieve(
    new RetrieveRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h4>Certificates Revoke</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Certyfikaty/paths/~1certificates~1%7BcertificateSerialNumber%7D~1revoke/post

```php
use N1ebieski\KSEFClient\Requests\Certificates\Revoke\RevokeRequest;

$response = $client->certificates()->revoke(
    new RevokeRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h4>Certificates Query</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Certyfikaty/paths/~1certificates~1query/post

```php
use N1ebieski\KSEFClient\Requests\Certificates\Query\QueryRequest;

$response = $client->certificates()->query(
    new QueryRequest(...)
)->object();
```
</details>

### Tokens

<details>
    <summary>
        <h4>Tokens Create</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Tokeny-KSeF/paths/~1tokens/post

```php
use N1ebieski\KSEFClient\Requests\Tokens\Create\CreateRequest;

$response = $client->tokens()->create(
    new CreateRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h4>Tokens List</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Tokeny-KSeF/paths/~1tokens/get

```php
use N1ebieski\KSEFClient\Requests\Tokens\List\ListRequest;

$response = $client->tokens()->list(
    new ListRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h4>Tokens Status</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Tokeny-KSeF/paths/~1tokens~1%7BreferenceNumber%7D/get

```php
use N1ebieski\KSEFClient\Requests\Tokens\Status\StatusRequest;

$response = $client->tokens()->list(
    new StatusRequest(...)
)->object();
```
</details>

<details>
    <summary>
        <h4>Tokens Revoke</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Tokeny-KSeF/paths/~1tokens~1%7BreferenceNumber%7D/delete

```php
use N1ebieski\KSEFClient\Requests\Tokens\Revoke\RevokeRequest;

$response = $client->tokens()->revoke(
    new RevokeRequest(...)
)->status();
```
</details>

### Peppol

<details>
    <summary>
        <h4>Peppol Query</h4>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Uslugi-Peppol/paths/~1peppol~1query/get

```php
use N1ebieski\KSEFClient\Requests\Peppol\Query\QueryRequest;

$response = $client->peppol()->query(
    new QueryRequest(...)
)->object();
```
</details>

### Testdata

#### Testdata Attachment

<details>
    <summary>
        <h5>Testdata Attachment Approve</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Dane-testowe/paths/~1testdata~1attachment/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Attachment\Grant\ApproveRequest;

$response = $client->testdata()->attachment()->approve(
    new ApproveRequest(...)
)->status();
```

</details>


<details>
    <summary>
        <h5>Testdata Attachment Revoke</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Dane-testowe/paths/~1testdata~1attachment~1revoke/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Attachment\Revoke\RevokeRequest;

$response = $client->testdata()->attachment()->revoke(
    new RevokeRequest(...)
)->status();
```

</details>

#### Testdata Person

<details>
    <summary>
        <h5>Testdata Person Create</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Dane-testowe/paths/~1testdata~1person/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Person\Create\CreateRequest;

$response = $client->testdata()->person()->create(
    new CreateRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h5>Testdata Person Remove</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Dane-testowe/paths/~1testdata~1person~1remove/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Person\Remove\RemoveRequest;

$response = $client->testdata()->person()->remove(
    new RemoveRequest(...)
)->status();
```
</details>

#### Testdata Context

<details>
    <summary>
        <h5>Testdata Context Block</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Dane-testowe/paths/~1testdata~1context~1block/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Context\Block\BlockRequest;

$response = $client->testdata()->context()->block(
    new BlockRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h5>Testdata Context Unblock</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Dane-testowe/paths/~1testdata~1context~1unblock/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Context\Unblock\UnblockRequest;

$response = $client->testdata()->context()->unblock(
    new UnblockRequest(...)
)->status();
```
</details>

#### Testdata Permissions

<details>
    <summary>
        <h5>Testdata Permissions Grants</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Dane-testowe/paths/~1testdata~1permissions/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Permissions\Grants\GrantsRequest;

$response = $client->testdata()->permissions()->grants(
    new GrantsRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h5>Testdata Permissions Revoke</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Dane-testowe/paths/~1testdata~1permissions~1revoke/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Permissions\Revoke\RevokeRequest;

$response = $client->testdata()->permissions()->revoke(
    new RevokeRequest(...)
)->status();
```
</details>

#### Testdata Limits

##### Testdata Limits Context

###### Testdata Limits Context Session

<details>
    <summary>
        <h5>Testdata Limits Context Session Limits</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1testdata~1limits~1context~1session/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Limits\Context\Session\Limits\LimitsRequest;

$response = $client->testdata()->limits()->context()->session()->limits(
    new LimitsRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h5>Testdata Limits Context Session Reset</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1testdata~1limits~1context~1session/delete

```php
use N1ebieski\KSEFClient\Requests\Testdata\Limits\Context\Session\Reset\ResetRequest;

$response = $client->testdata()->limits()->context()->session()->reset(
    new ResetRequest(...)
)->status();
```
</details>

##### Testdata Limits Subject

###### Testdata Limits Subject Certificate

<details>
    <summary>
        <h5>Testdata Limits Subject Certificate Limits</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1testdata~1limits~1subject~1certificate/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\Limits\Subject\Certificate\Limits\LimitsRequest;

$response = $client->testdata()->limits()->subject()->certificate()->limits(
    new LimitsRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h5>Testdata Limits Subject Certificate Reset</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1testdata~1limits~1subject~1certificate/delete

```php
use N1ebieski\KSEFClient\Requests\Testdata\Limits\Subject\Certificate\Reset\ResetRequest;

$response = $client->testdata()->limits()->subject()->certificate()->reset(
    new ResetRequest(...)
)->status();
```
</details>

#### Testdata Rate Limits

<details>
    <summary>
        <h5>Testdata Rate Limits Limits</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1testdata~1rate-limits/post

```php
use N1ebieski\KSEFClient\Requests\Testdata\RateLimits\Limits\LimitsRequest;

$response = $client->testdata()->rateLimits()->limits(
    new LimitsRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h5>Testdata Rate Limits Reset</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1testdata~1rate-limits/delete

```php
use N1ebieski\KSEFClient\Requests\Testdata\RateLimits\Reset\ResetRequest;

$response = $client->testdata()->rateLimits()->reset(
    new ResetRequest(...)
)->status();
```
</details>

<details>
    <summary>
        <h5>Testdata Rate Limits Production</h5>
    </summary>

https://api-test.ksef.mf.gov.pl/docs/v2/index.html#tag/Limity-i-ograniczenia/paths/~1testdata~1rate-limits/delete

```php
use N1ebieski\KSEFClient\Requests\Testdata\RateLimits\Production\ProductionRequest;

$response = $client->testdata()->rateLimits()->production(
    new ProductionRequest(...)
)->status();
```
</details>

### Latarnia

<details>
    <summary>
        <h4>Latarnia Status</h4>
    </summary>

https://github.com/CIRFMF/ksef-latarnia/blob/main/scenariusze.md

```php
$response = $client->latarnia()->status()->object();
```
</details>

<details>
    <summary>
        <h4>Latarnia Messages</h4>
    </summary>

https://github.com/CIRFMF/ksef-latarnia/blob/main/scenariusze.md

```php
$response = $client->latarnia()->messages()->object();
```
</details>

## Examples

### Integration with a frontend application using certificate-based authentication

https://github.com/N1ebieski/ksef-app-example.test

<details>
    <summary>
        <h3>Conversion of the KSEF certificate and private key from MCU to a .p12 file</h3>
    </summary>

```php
use N1ebieski\KSEFClient\Actions\ConvertCertificateToPkcs12\ConvertCertificateToPkcs12Action;
use N1ebieski\KSEFClient\Actions\ConvertCertificateToPkcs12\ConvertCertificateToPkcs12Handler;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Factories\CertificateFactory;

$certificate = file_get_contents(Utility::basePath('config/certificates/certificate.crt'));

$privateKey = file_get_contents(Utility::basePath('config/certificates/privateKey.key'));

$certificateToPkcs12 = (new ConvertCertificateToPkcs12Handler())->handle(
    new ConvertCertificateToPkcs12Action(
        certificate: CertificateFactory::makeFromPkcs8($certificate, $privateKey, 'password'),
        passphrase: 'password'
    )
);

file_put_contents(Utility::basePath('config/certificates/ksef-certificate.p12'), $certificateToPkcs12);
```
</details>

<details>
    <summary>
        <h3>Generate a KSEF certificate and convert to a .p12 file</h3>
    </summary>

```php
<?php

use N1ebieski\KSEFClient\Actions\ConvertCertificateToPkcs12\ConvertCertificateToPkcs12Action;
use N1ebieski\KSEFClient\Actions\ConvertCertificateToPkcs12\ConvertCertificateToPkcs12Handler;
use N1ebieski\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemAction;
use N1ebieski\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemHandler;
use N1ebieski\KSEFClient\Actions\ConvertPemToDer\ConvertPemToDerAction;
use N1ebieski\KSEFClient\Actions\ConvertPemToDer\ConvertPemToDerHandler;
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\DTOs\DN;
use N1ebieski\KSEFClient\Factories\CSRFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\PrivateKeyType;

$client = (new ClientBuilder())
    ->withMode(Mode::Test)
    ->withIdentifier('NIP_NUMBER')
    // To generate the KSEF certificate, you have to authorize the qualified certificate the first time
    ->withCertificatePath($_ENV['PATH_TO_CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE'])
    ->build();

$dataResponse = $client->certificates()->enrollments()->data()->json();

$dn = DN::from($dataResponse);

// You can choose beetween EC or RSA private key type
$csr = CSRFactory::make($dn, PrivateKeyType::EC);

$csrToDer = (new ConvertPemToDerHandler())->handle(new ConvertPemToDerAction($csr->raw));

$sendResponse = $client->certificates()->enrollments()->send([
    'certificateName' => 'My first certificate',
    'certificateType' => 'Authentication',
    'csr' => base64_encode($csrToDer),
])->object();

$statusResponse = Utility::retry(function () use ($client, $sendResponse) {
    $statusResponse = $client->certificates()->enrollments()->status([
        'referenceNumber' => $sendResponse->referenceNumber
    ])->object();

    if ($statusResponse->status->code === 200) {
        return $statusResponse;
    }

    if ($statusResponse->status->code >= 400) {
        throw new RuntimeException(
            $statusResponse->status->description,
            $statusResponse->status->code
        );
    }
});

$retrieveResponse = $client->certificates()->retrieve([
    'certificateSerialNumbers' => [$statusResponse->certificateSerialNumber]
])->object();

$certificate = base64_decode($retrieveResponse->certificates[0]->certificate);

$certificateToPem = (new ConvertDerToPemHandler())->handle(
    new ConvertDerToPemAction($certificate, 'CERTIFICATE')
);

$certificateToPkcs12 = (new ConvertCertificateToPkcs12Handler())->handle(
    new ConvertCertificateToPkcs12Action(
        certificate: CertificateFactory::makeFromPkcs8($certificateToPem, $csr->privateKey),
        passphrase: 'password'
    )
);

file_put_contents(Utility::basePath('config/certificates/ksef-certificate.p12'), $certificateToPkcs12);
```
</details>

<details>
    <summary>
        <h3>Send an invoice, check for UPO and generate QR code</h3>
    </summary>

```php
<?php

use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use N1ebieski\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesAction;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesHandler;
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\DTOs\QRCodes;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\Requests\KsefNumber;

$encryptionKey = EncryptionKeyFactory::makeRandom();

$nip = 'NIP_NUMBER';

$client = (new ClientBuilder())
    ->withMode(Mode::Test)
    ->withIdentifier($nip)
    ->withCertificatePath($_ENV['PATH_TO_CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE'])
    ->withEncryptionKey($encryptionKey)
    ->build();

$openResponse = $client->sessions()->online()->open([
    'formCode' => 'FA (3)',
])->object();

$fakturaFixture = (new FakturaSprzedazyTowaruFixture())
    ->withRandomInvoiceNumber()
    ->withNip($nip)
    ->withTodayDate();

// For sending FA (3) use Sessions\Faktura
// For sending FA_RR (1) use Sessions\FakturaRR\Faktura
$faktura = Faktura::from($fakturaFixture->data);

// For sending invoice as DTO use SendRequest or array
// For sending invoice as XML use SendXmlRequest
$sendResponse = $client->sessions()->online()->send([
    'faktura' => $faktura,
    'referenceNumber' => $openResponse->referenceNumber,
])->object();

$closeResponse = $client->sessions()->online()->close([
    'referenceNumber' => $openResponse->referenceNumber
]);

$statusResponse = Utility::retry(function () use ($client, $openResponse, $sendResponse) {
    $statusResponse = $client->sessions()->invoices()->status([
        'referenceNumber' => $openResponse->referenceNumber,
        'invoiceReferenceNumber' => $sendResponse->referenceNumber
    ])->object();

    if ($statusResponse->status->code === 200) {
        return $statusResponse;
    }

    if ($statusResponse->status->code >= 400) {
        throw new RuntimeException(
            $statusResponse->status->description,
            $statusResponse->status->code
        );
    }
});

$upo = $client->sessions()->invoices()->upo([
    'referenceNumber' => $openResponse->referenceNumber,
    'invoiceReferenceNumber' => $sendResponse->referenceNumber
])->body();

$generateQRCodesHandler = new GenerateQRCodesHandler(
    qrCodeBuilder: (new QrCodeBuilder())
        ->roundBlockSizeMode(RoundBlockSizeMode::Enlarge)
        ->labelFont(new OpenSans(size: 12)),
    convertEcdsaDerToRawHandler: new ConvertEcdsaDerToRawHandler()
);

$ksefNumber = KsefNumber::from($statusResponse->ksefNumber);

// For generating QR code by document use GenerateQRCodesAction
// For generating QR code by invoice hash use GenerateQRCodesByInvoiceHashAction

/** @var QRCodes $qrCodes */
$qrCodes = $generateQRCodesHandler->handle(new GenerateQRCodesAction(
    mode: Mode::Test,
    nip: $faktura->podmiot1->daneIdentyfikacyjne->nip,
    invoiceCreatedAt: $faktura->fa->p_1->value,
    document: $faktura->toXml(),
    ksefNumber: $ksefNumber
));

// Invoice link
file_put_contents(Utility::basePath("var/qr/code1.png"), $qrCodes->code1->raw);
```
</details>

<details>
    <summary>
        <h3>Generate PDF for the invoice and the UPO file</h3>
    </summary>

[PDF invoice example.pdf](https://github.com/user-attachments/files/23744618/ONLINE.pdf)
[UPO-example.pdf](https://github.com/user-attachments/files/23747747/UPO-20251124-EE-4148363000-80035B5E1C-6A.pdf)

Install [n1ebieski/ksef-pdf-generator](https://github.com/N1ebieski/ksef-pdf-generator/tree/feature/cli)

```php
use N1ebieski\KSEFClient\Actions\GeneratePDF\GeneratePDFAction;
use N1ebieski\KSEFClient\Actions\GeneratePDF\GeneratePDFHandler;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\ValueObjects\KsefFeInvoiceConverterPath;

// Send an invoice using example https://github.com/N1ebieski/ksef-php-client?tab=readme-ov-file#send-an-invoice-check-for-upo-and-generate-qr-code

// and then...

$ksefFeInvoiceConverterPath = KsefFeInvoiceConverterPath::from(Utility::basePath('../ksef-fe-invoice-converter/dist/cli/index.js'));

$pdfs = (new GeneratePDFHandler())->handle(new GeneratePDFAction(
    ksefFeInvoiceConverterPath: $ksefFeInvoiceConverterPath,    
    invoiceDocument $faktura->toXml(),
    upoDocument: $upo,
    qrCodes: $qrCodes,
    ksefNumber: $ksefNumber
));

file_put_contents(Utility::basePath("var/pdf/{$ksefNumber->value}.pdf"), $pdfs->invoice);
file_put_contents(Utility::basePath("var/pdf/UPO-{$sendResponse->referenceNumber}.pdf"), $pdfs->upo);
```

</details>

<details>
    <summary>
        <h3>Generate PDF for the transaction confirmation with both QR codes</h3>
    </summary>

[PDF confirmation example.pdf](https://github.com/user-attachments/files/24212098/CONFIRMATION-INV-6942913FCDB55.pdf)

Read https://ksef.podatki.gov.pl/informacje-ogolne-ksef-20/potwierdzenie-transakcji/

Install [n1ebieski/ksef-pdf-generator](https://github.com/N1ebieski/ksef-pdf-generator/tree/feature/cli)

```php
use N1ebieski\KSEFClient\Actions\GeneratePDF\GeneratePDFAction;
use N1ebieski\KSEFClient\Actions\GeneratePDF\GeneratePDFHandler;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\ValueObjects\KsefFeInvoiceConverterPath;

// Create an online invoice using example https://github.com/N1ebieski/ksef-php-client?tab=readme-ov-file#send-an-invoice-check-for-upo-and-generate-qr-code

// and then...

$ksefFeInvoiceConverterPath = KsefFeInvoiceConverterPath::from(Utility::basePath('../ksef-fe-invoice-converter/dist/cli/index.js'));

$pdfs = (new GeneratePDFHandler())->handle(new GeneratePDFAction(
    ksefFeInvoiceConverterPath: $ksefFeInvoiceConverterPath,    
    confirmationDocument: $faktura->toXml(),
    qrCodes: $qrCodes
));

file_put_contents(Utility::basePath("var/pdf/CONFIRMATION-{$faktura->fa->p_2->value}.pdf"), $pdfs->confirmation);
```

</details>

<details>
    <summary>
        <h3>Batch async send multiple invoices and check for UPO</h3>
    </summary>

```php
<?php

use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\ValueObjects\Mode;

$encryptionKey = EncryptionKeyFactory::makeRandom();

$nip = 'NIP_NUMBER';

$client = (new ClientBuilder())
    ->withMode(Mode::Test)
    ->withIdentifier($nip)
    ->withCertificatePath($_ENV['PATH_TO_CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE'])
    ->withEncryptionKey($encryptionKey)
    ->build();

$faktury = array_map(
    fn () => (new FakturaSprzedazyTowaruFixture())
        ->withTodayDate()
        ->withNip($nip)
        ->withRandomInvoiceNumber()
        ->data,
    range(1, 100)
);

// For sending invoices as DTOs use OpenAndSendRequest or array
// For sending invoices as XMLs use OpenAndSendXmlRequest
// For sending invoices as ZIP use OpenAndSendZipRequest
$openAndSendResponse = $client->sessions()->batch()->openAndSend([
    'formCode' => 'FA (3)',
    'faktury' => $faktury
]);

$openResponse = $openAndSendResponse->object();

$partUploadResponses = $openAndSendResponse->partUploadResponses;

$client->sessions()->batch()->close([
    'referenceNumber' => $openResponse->referenceNumber
]);

$statusResponse = Utility::retry(function () use ($client, $openResponse) {
    $statusResponse = $client->sessions()->status([
        'referenceNumber' => $openResponse->referenceNumber,
    ])->object();

    if ($statusResponse->status->code === 200) {
        return $statusResponse;
    }

    if ($statusResponse->status->code >= 400) {
        throw new RuntimeException(
            $statusResponse->status->description,
            $statusResponse->status->code
        );
    }
});

$upo = file_get_contents($statusResponse->upo->pages[0]->downloadUrl);
```

</details>

<details>
    <summary>
        <h3>Create an offline invoice and generate both QR codes</h3>
    </summary>

```php
<?php

use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use N1ebieski\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesAction;
use N1ebieski\KSEFClient\Actions\GenerateQRCodes\GenerateQRCodesHandler;
use N1ebieski\KSEFClient\DTOs\QRCodes;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;
use N1ebieski\KSEFClient\Factories\CertificateFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use N1ebieski\KSEFClient\ValueObjects\CertificateSerialNumber;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\NIP;

$nip = 'NIP_NUMBER';

// Remember: this certificate must be "Offline" type, not "Authentication"
$certificate = CertificateFactory::makeFromCertificatePath(
    CertificatePath::from($_ENV['PATH_TO_CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE'])
);

$fakturaFixture = (new FakturaSprzedazyTowaruFixture())
    ->withTodayDate()
    ->withNip($nip)
    ->withRandomInvoiceNumber();

// For creating FA (3) use Sessions\Faktura
// For creating FA_RR (1) use Sessions\FakturaRR\Faktura
$faktura = Faktura::from($fakturaFixture->data);

$generateQRCodesHandler = new GenerateQRCodesHandler(
    qrCodeBuilder: (new QrCodeBuilder())
        ->roundBlockSizeMode(RoundBlockSizeMode::Enlarge)
        ->labelFont(new OpenSans(size: 12)),
    convertEcdsaDerToRawHandler: new ConvertEcdsaDerToRawHandler()
);

$contextIdentifierGroup = ContextIdentifierGroup::fromIdentifier(NIP::from($nip));

// For generating QR codes by document use GenerateQRCodesAction
// For generating QR codes by invoice hash use GenerateQRCodesByInvoiceHashAction

/** @var QRCodes $qrCodes */
$qrCodes = $generateQRCodesHandler->handle(new GenerateQRCodesAction(
    mode: Mode::Test,
    nip: $faktura->podmiot1->daneIdentyfikacyjne->nip,
    invoiceCreatedAt: $faktura->fa->p_1->value,
    document: $faktura->toXml(),
    certificate: $certificate,
    contextIdentifierGroup: $contextIdentifierGroup
));

// Invoice link
file_put_contents(Utility::basePath("var/qr/code1.png"), $qrCodes->code1->raw);

// Certificate verification link
file_put_contents(Utility::basePath("var/qr/code2.png"), $qrCodes->code2->raw);
```

</details>

<details>
    <summary>
        <h3>Generate PDF for the offline invoice file with both QR codes</h3>
    </summary>

[PDF offline invoice EC certificate example.pdf](https://github.com/user-attachments/files/23744619/OFFLINE-CERTYFIKAT-EC.pdf)
[PDF offline invoice RSA certificate example.pdf](https://github.com/user-attachments/files/23744620/OFFLINE-CERTYFIKAT-RSA.pdf)

Install [n1ebieski/ksef-pdf-generator](https://github.com/N1ebieski/ksef-pdf-generator/tree/feature/cli)

```php
use N1ebieski\KSEFClient\Actions\GeneratePDF\GeneratePDFAction;
use N1ebieski\KSEFClient\Actions\GeneratePDF\GeneratePDFHandler;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\ValueObjects\KsefFeInvoiceConverterPath;

// Create an offline invoice using example https://github.com/N1ebieski/ksef-php-client?tab=readme-ov-file#create-an-offline-invoice-and-generate-both-qr-codes

// and then...

$ksefFeInvoiceConverterPath = KsefFeInvoiceConverterPath::from(Utility::basePath('../ksef-fe-invoice-converter/dist/cli/index.js'));

$pdfs = (new GeneratePDFHandler())->handle(new GeneratePDFAction(
    ksefFeInvoiceConverterPath: $ksefFeInvoiceConverterPath,    
    invoiceDocument: $faktura->toXml(),
    qrCodes: $qrCodes
));

file_put_contents(Utility::basePath("var/pdf/{$faktura->fa->p_2->value}.pdf"), $pdfs->invoice);
```

</details>

<details>
    <summary>
        <h3>Download and decrypt invoices using the encryption key</h3>
    </summary>

```php
<?php

use N1ebieski\KSEFClient\Actions\DecryptDocument\DecryptDocumentAction;
use N1ebieski\KSEFClient\Actions\DecryptDocument\DecryptDocumentHandler;
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\DTOs\Requests\Sessions\Faktura;

$encryptionKey = EncryptionKeyFactory::makeRandom();

$client = (new ClientBuilder())
    ->withMode(Mode::Test)
    ->withIdentifier($_ENV['NIP_NUMBER'])
    ->withCertificatePath($_ENV['PATH_TO_CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE'])
    ->withEncryptionKey($encryptionKey)
    ->build();

$initResponse = $client->invoices()->exports()->init([
    'filters' => [
        'subjectType' => 'Subject1',
        'dateRange' => [
            'dateType' => 'Invoicing',
            'from' => new DateTimeImmutable('-1 day', new DateTimeZone('UTC')),
            'to' => new DateTimeImmutable('now', new DateTimeZone('UTC'))
        ],
    ]
])->object();

$statusResponse = Utility::retry(function () use ($client, $initResponse) {
    $statusResponse = $client->invoices()->exports()->status([
        'referenceNumber' => $initResponse->referenceNumber
    ])->object();

    if ($statusResponse->status->code === 200) {
        return $statusResponse;
    }

    if ($statusResponse->status->code >= 400) {
        throw new RuntimeException(
            $statusResponse->status->description,
            $statusResponse->status->code
        );
    }
});

$decryptDocumentHandler = new DecryptDocumentHandler();

$zipContents = '';

// Downloading...
foreach ($statusResponse->package->parts as $part) {
    $contents = file_get_contents($part->url);

    $contents = $decryptDocumentHandler->handle(new DecryptDocumentAction(
        document: $contents,
        encryptionKey: $encryptionKey
    ));

    $zipContents .= $contents;
}

file_put_contents(Utility::basePath("var/zip/invoices.zip"), $zipContents);

// Invoices can be deserialize to Faktura DTO

$faktury = [];

$zip = new ZipArchive();

$zip->open(Utility::basePath("var/zip/invoices.zip"));

for ($i = 0; $i < $zip->numFiles; $i++) {
    $filename = $zip->getNameIndex($i);

    if (str_ends_with($filename, '.xml')) {
        $content = $zip->getFromIndex($i);

        $faktury[] = Faktura::fromXml($content);
    }
}

$zip->close();
```
</details>

## Testing

The package uses unit and feature tests via [Pest](https://pestphp.com). 

Pest configuration is located in ```tests/Pest```

Fake request and responses fixtures for resources are located in ```src/Testing/Fixtures/Requests```

Run all tests:

```bash
composer install
```

```bash
vendor/bin/pest --parallel
```

## Roadmap

1. **23.03.2026** – Release of **v1.0.0-rc.\*** (from this point, avoid any breaking changes unless KSeF team forces us)
2. **01.04.2026** – Release of **v1.0.0** (production ready)
3. **End of 2026** – The release of the major version drops support for PHP 8.1–8.3 and upgrades the package to utilize features and dependencies of PHP 8.4.

## Special thanks

Special thanks to:

- all the [contributors](https://github.com/N1ebieski/ksef-php-client/graphs/contributors)
- all the helpful people on the [4programmers.net](https://4programmers.net/Forum/Nietuzinkowe_tematy/355933-krajowy_system_e_faktur) forum and in the [issue reports](https://github.com/N1ebieski/ksef-php-client/issues)
- authors of the repository [grafinet/xades-tools](https://github.com/grafinet/xades-tools) for the Xades document signing tool
- Łukasz Wojtanowski - author of a modified version of the official ksef-pdf-generator, available at [lukasz-wojtanowski-softvig/ksef-pdf-generator](https://github.com/lukasz-wojtanowski-softvig/ksef-pdf-generator/tree/feature/cli), which adds support for generating invoice and UPO PDFs via CLI
