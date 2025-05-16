<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Session\InitSigned;

use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedResponse;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitSigned\InitSignedRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitSigned\InitSignedResponseFixture;

final class InitSignedHandlerTest extends AbstractTestCase
{
    public function testValidResponse(): void
    {
        $requestFixture = new InitSignedRequestFixture();
        $responseFixture = new InitSignedResponseFixture();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->session()->initSigned($requestFixture->data);

        $this->assertInstanceOf(InitSignedResponse::class, $response);

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new InitSignedRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->session()->initSigned($requestFixture->data);
    }
}
