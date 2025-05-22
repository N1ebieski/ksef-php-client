<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Invoice\Send;

use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\FakturaSprzedazyTowaruRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\SendResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class SendHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{FakturaSprzedazyTowaruRequestFixture, SendResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new FakturaSprzedazyTowaruRequestFixture(),
        ];

        $responses = [
            new SendResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{FakturaSprzedazyTowaruRequestFixture, SendResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(FakturaSprzedazyTowaruRequestFixture $requestFixture, SendResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = SendRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->online()->invoice()->send($requestFixture->data)->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new FakturaSprzedazyTowaruRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->invoice()->send($requestFixture->data);
    }
}
