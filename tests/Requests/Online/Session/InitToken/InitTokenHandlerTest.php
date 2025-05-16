<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Session\InitToken;

use N1ebieski\KSEFClient\Requests\Online\Session\InitToken\InitTokenResponse;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitToken\InitTokenRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitToken\InitTokenResponseFixture;

final class InitTokenHandlerTest extends AbstractTestCase
{
    public function testValidResponse(): void
    {
        $requestFixture = new InitTokenRequestFixture();
        $responseFixture = new InitTokenResponseFixture();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->session()->initToken($requestFixture->data);

        $this->assertInstanceOf(InitTokenResponse::class, $response);

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
