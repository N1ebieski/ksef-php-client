<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Invoice\Get;

use N1ebieski\KSEFClient\Requests\Online\Invoice\Get\GetRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Get\GetRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Get\GetResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class GetHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{GetRequestFixture, GetResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new GetRequestFixture(),
        ];

        $responses = [
            new GetResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{GetRequestFixture, GetResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(GetRequestFixture $requestFixture, GetResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = GetRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->online()->invoice()->get($requestFixture->data)->body();

        $this->assertSame($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new GetRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->invoice()->get($requestFixture->data);
    }
}
