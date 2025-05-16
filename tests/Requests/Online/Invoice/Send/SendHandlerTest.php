<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Invoice\Send;

use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendResponse;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\SendRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\SendResponseFixture;

final class SendHandlerTest extends AbstractTestCase
{
    public function testValidResponse(): void
    {
        $requestFixture = new SendRequestFixture();
        $responseFixture = new SendResponseFixture();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->invoice()->send($requestFixture->data);

        $this->assertInstanceOf(SendResponse::class, $response);

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new SendRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->invoice()->send($requestFixture->data);
    }
}
