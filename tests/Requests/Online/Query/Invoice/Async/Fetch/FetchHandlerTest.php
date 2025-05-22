<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Query\Invoice\Async\Fetch;

use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Async\Fetch\FetchRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Async\Fetch\FetchRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Async\Fetch\FetchResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{FetchRequestFixture, FetchResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new FetchRequestFixture()
        ];

        $responses = [
            new FetchResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{FetchRequestFixture, FetchResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(FetchRequestFixture $requestFixture, FetchResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = FetchRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->online()->query()->invoice()->async()->fetch($request)->body();

        $this->assertSame($responseFixture->data, $response);
    }

    #[DataProvider('validResponseProvider')]
    public function testValidEncryptedResponse(FetchRequestFixture $requestFixture, FetchResponseFixture $responseFixture): void
    {
        $encryptionKey = EncryptionKeyFactory::makeRandom();

        $encryptedResponseFixture = (clone $responseFixture)->withEncryptionKey($encryptionKey);

        $clientStub = $this->getClientStub($encryptedResponseFixture, $encryptionKey);

        $request = FetchRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->online()->query()->invoice()->async()->fetch($request)->body();

        $this->assertSame($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new FetchRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->invoice()->send($requestFixture->data);
    }
}
