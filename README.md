# KSEF PHP Client

PHP API client that allows you to interact with the [API Krajowego Systemu e-Faktur](https://www.gov.pl/web/kas/api-krajowego-system-e-faktur)

## Table of Contents

- [Get Started](#get-started)
  - [Auto authorization via API Token](#auto-authorization-via-api-token)

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

Then, interact with KSEF's API:

### Auto authorization via API Token

```php
use N1ebieski\KSEFClient\ClientBuilder;

$client = new ClientBuilder()
    ->withApiToken($_ENV['KSEF_KEY'])
    ->withKSEFPublicKeyPath($_ENV['PATH_TO_KSEF_PUBLIC_KEY'])
    ->build();

// Do something with the available resources

$client->online()->session()->terminate();
```

### Auto authorization via certificate .p12

```php
use N1ebieski\KSEFClient\ClientBuilder;

$client = new ClientBuilder()
    ->withCertificatePath($_ENV['PATH_TO_CERTIFICATE'], 'passphrase')
    ->build();

// Do something with the available resources

$client->online()->session()->terminate();
```

### Manual authorization

```php
use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequest;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedXmlRequest;
use N1ebieski\KSEFClient\ValueObjects\NIP;

$client = new ClientBuilder()->build();

$authorisationChallengeResponse = $client->online()->session()->authorisationChallenge(
    new AuthorisationChallengeRequest(NIP::from('NIP-NUMBER'))
);

$xml = file_get_contents('PATH_TO_SIGNED_XML');

$initSignedResponse = $client->online()->session()->initSigned(
    new InitSignedXmlRequest($xml)
);

$client = $client->withSessionToken($initSignedResponse->sessionToken->token);

// Do something with the available resources

$client->online()->session()->terminate();
```