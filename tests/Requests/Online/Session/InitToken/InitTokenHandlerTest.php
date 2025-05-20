<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Session\InitToken;

use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitToken\InitTokenRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitToken\InitTokenResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class InitTokenHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{InitTokenRequestFixture, InitTokenResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new InitTokenRequestFixture(),
        ];

        $responses = [
            new InitTokenResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{InitTokenRequestFixture, InitTokenResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(InitTokenRequestFixture $requestFixture, InitTokenResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = InitTokenRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->online()->session()->initToken($request)->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new InitTokenRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->session()->initToken($requestFixture->data);
    }
}
