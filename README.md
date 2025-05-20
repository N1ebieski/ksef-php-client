# KSEF PHP Client

> **This package is not production ready yet!**

PHP API client that allows you to interact with the [API Krajowego Systemu e-Faktur](https://www.gov.pl/web/kas/api-krajowego-system-e-faktur)

## Table of Contents

- [Get Started](#get-started)
    - [Client configuration](#client-configuration)
    - [Auto mapping](#auto-mapping)
- [Authorization](#authorization)
    - [Auto authorization via API Token](#auto-authorization-via-api-token)
    - [Auto authorization via certificate .p12](#auto-authorization-via-certificate-p12)
    - [Manual authorization](#manual-authorization)
- [Resources](#resources)
    - [Common](#common)
        - [Status](#status)        
    - [Online](#online)
        - [Session](#session)
            - [Authorisation challenge](#authorization-challenge)
            - [Init token](#init-token)
            - [Init signed](#init-signed)
            - [Session status](#session-status)
            - [Terminate](#terminate)
        - [Invoice](#invoice)
            - [Get an invoice](#get-an-invoice)
            - [Send an invoice](#send-an-invoice)
            - [Invoice status](#invoice-status)
- [Examples](#examples)
    - [Send an invoice and check for UPO](#send-an-invoice-and-check-for-upo)
- [Testing](#testing)

## Get Started

> **Requires [PHP 8.4+](https://www.php.net/releases/)**

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
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;

$client = new ClientBuilder()
    ->withMode(Mode::Production) // Choice between: Test, Demo, Production
    ->withApiUrl($_ENV['KSEF_API_URL']) // Optional, default is set by Mode selection
    ->withHttpClient(new \GuzzleHttp\Client([])) // Optional, default is set by Psr18ClientDiscovery::find()
    ->withApiToken($_ENV['KSEF_KEY']) // Required for API Token authorization
    ->withKSEFPublicKeyPath($_ENV['PATH_TO_KSEF_PUBLIC_KEY']) // Required for API Token authorization and encryption, you can find it on https://ksef.mf.gov.pl
    ->withCertificatePath($_ENV['PATH_TO_CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE']) // Required .p12 file for Certificate authorization
    ->withEncryptionKey(EncryptionKeyFactory::makeRandom()) // Optional for online resources, required for batch resources. Remember to save this value!
    ->withNIP('NIP_NUMBER') // Required for Mode::Production and Mode::Demo, optional for Mode::Test
    ->withLogXmlPath('PATH_TO_SAVE_XML_FILES') // Some endpoints generate xml files, useful for debug
    ->build();
```

### Auto mapping

Each resource supports mapping through both an array and a DTO, for example:

```php
use N1ebieski\KSEFClient\Requests\Common\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\ValueObjects\ReferenceNumber;

$commonStatus = $client->common()->status(new StatusRequest(
    referenceNumber: ReferenceNumber::from('20250508-EE-B395BBC9CD-A7DB4E6095-BD')
))->object();
```

```php
$commonStatus = $client->common()->status([
    'reference_number' => '20250508-EE-B395BBC9CD-A7DB4E6095-BD'
])->object();
```

## Authorization

### Auto authorization via API Token

```php
use N1ebieski\KSEFClient\ClientBuilder;

$client = new ClientBuilder()
    ->withApiToken($_ENV['KSEF_KEY'])
    ->withKSEFPublicKeyPath($_ENV['PATH_TO_KSEF_PUBLIC_KEY'])
    ->withNIP('NIP_NUMBER')
    ->build();

// Do something with the available resources

$client->online()->session()->terminate();
```

### Auto authorization via certificate .p12

```php
use N1ebieski\KSEFClient\ClientBuilder;

$client = new ClientBuilder()
    ->withCertificatePath($_ENV['PATH_TO_CERTIFICATE'], $_ENV['CERTIFICATE_PASSPHRASE'])
    ->withKSEFPublicKeyPath($_ENV['PATH_TO_KSEF_PUBLIC_KEY'])
    ->withNIP('NIP_NUMBER')
    ->build();

// Do something with the available resources

$client->online()->session()->terminate();
```

### Manual authorization

```php
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedXmlRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\ValueObjects\Challenge;
use N1ebieski\KSEFClient\ValueObjects\NIP;
use DateTimeImmutable;

$client = new ClientBuilder()
    ->withKSEFPublicKeyPath($_ENV['PATH_TO_KSEF_PUBLIC_KEY'])
    ->build();

$nip = NIP::from('NIP_NUMBER');

$authorisationChallengeResponse = $client->online()->session()->authorisationChallenge(
    new AuthorisationChallengeRequest($nip)
)->object();

$xml = new InitSignedRequest(
    challenge: Challenge::from($authorisationChallengeResponse->challenge),
    timestamp: new DateTimeImmutable($authorisationChallengeResponse->timestamp),
    nip: $nip
)->toXml();

$signedXml = // Sign a xml document via Szafir, ePUAP etc.

$initSignedResponse = $client->online()->session()->initSigned(
    new InitSignedXmlRequest($signedXml)
)->object();

$client = $client->withSessionToken($initSignedResponse->sessionToken->token);

// Do something with the available resources

$client->online()->session()->terminate();
```

## Resources

### Common

#### Status

Checking the status of batch processing (with UPO after finalization)

```php
use N1ebieski\KSEFClient\Requests\Common\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Common\Status\StatusResponse;

/** @var StatusResponse $response */
$response = $client->common()->status(
    new StatusRequest(...)
)->object();
```

### Online

#### Session

##### Authorisation challenge

Initialize the authentication and authorization mechanism.

```php
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeResponse;

/** @var AuthorisationChallengeResponse $response */
$response = $client->online()->session()->authorisationChallenge(
    new AuthorisationChallengeRequest(...)
)->object();
```

##### Init token

Initializing an interactive session. KSeF public key encrypted document http://ksef.mf.gov.pl/schema/gtw/svc/online/auth/request/2021/10/01/0001/InitSessionTokenRequest

```php
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenResponse;

/** @var InitTokenResponse $response */
$response = $client->online()->session()->initToken(
    new InitTokenRequest(...)
)->object();
```

##### Init signed

Initializing an interactive session. Signed document http://ksef.mf.gov.pl/schema/gtw/svc/online/auth/request/2021/10/01/0001/InitSessionSignedRequest

```php
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedResponse;

/** @var InitSignedResponse $response */
$response = $client->online()->session()->initSigned(
    new InitSignedRequest(...)
)->object();
```

or:

```php
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedXmlRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedResponse;

/** @var InitSignedResponse $response */
$response = $client->online()->session()->initSigned(
    new InitSignedXmlRequest($signedXml)
)->object();
```

##### Session status

Checking the status of current interactive processing or based on the reference number.

```php
use N1ebieski\KSEFClient\Requests\Online\Session\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\Status\StatusResponse;

/** @var StatusResponse $response */
$response = $client->online()->session()->status(
    new StatusRequest(...)
)->object();
```

##### Terminate

Forcing the closing of an active interactive session

```php
use N1ebieski\KSEFClient\Requests\Online\Session\Terminate\TerminateResponse;

/** @var TerminateResponse $response */
$response = $client->online()->session()->terminate()->object();
```

#### Invoice

##### Get an invoice

Invoice download.

```php
use N1ebieski\KSEFClient\Requests\Online\Invoice\Get\GetRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Get\GetResponse;

/** @var GetResponse $response */
$response = $client->online()->invoice()->get(
    new GetRequest(...)
)->body();
```

##### Send an invoice

```php
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendResponse;

/** @var SendResponse $response */
$response = $client->online()->invoice()->send(
    new SendRequest(...)
)->object();
```

##### Invoice status

Checking the status of a sent invoice.

```php
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusResponse;

/** @var StatusResponse $response */
$response = $client->online()->invoice()->status(
    new StatusRequest(...)
)->object();
```

## Examples

### Send an invoice and check for UPO

```php
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Requests\Common\Status\StatusResponse;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\SendRequestFixture;
use N1ebieski\KSEFClient\ValueObjects\Mode;

$client = new ClientBuilder()
    ->withMode(Mode::Test)
    ->withApiToken($_ENV['KSEF_KEY'])
    ->withLogXmlPath(__DIR__ . '/../var/xml/')
    ->withKSEFPublicKeyPath(__DIR__ . '/../config/keys/publicKey.pem')
    ->build();

try {
    // Send an invoice
    $sendResponse = $client->online()->invoice()->send(
        new SendRequestFixture()->withTodayDate()->data
    )->object();

    // Check status of invoice generation
    Utility::retry(function () use ($client, $sendResponse) {
        $statusResponse = $client->online()->invoice()->status([
            'invoice_element_reference_number' => $sendResponse->elementReferenceNumber
        ])->object();

        if ($statusResponse->processingCode === 200) {
            return $statusResponse;
        }
    });
} catch (Throwable $e) {
    $client->online()->session()->terminate();

    throw $e;
}

// Close session (only then will the UPO be generated)
$client->online()->session()->terminate();

// We don't need to authorize for UPO
$client = new ClientBuilder()
    ->withMode(Mode::Test)
    ->withKSEFPublicKeyPath(__DIR__ . '/../config/keys/publicKey.pem')
    ->build();

// Check status of UPO generation
/** @var StatusResponse $commonStatus */
$commonStatus = Utility::retry(function () use ($client, $sendResponse) {
    $commonStatus = $client->common()->status([
        'reference_number' => $sendResponse->referenceNumber
    ])->object();

    if ($commonStatus->processingCode === 200) {
        return $commonStatus;
    }
});

$xml = base64_decode($commonStatus->upo);
```

## Testing

The package uses unit tests via [PHPUnit](https://github.com/sebastianbergmann/phpunit). 

TestCase is located in the location of ```N1ebieski\KSEFClient\Testing\TestCase```

Fake request fixtures are located in the location of ```N1ebieski\KSEFClient\Testing\Fixtures\Requests```

Fake response fixtures are located in the location of ```N1ebieski\KSEFClient\Testing\Fixtures\Responses```

Run all tests:

```bash
composer install
```

```bash
vendor/bin/phpunit
```