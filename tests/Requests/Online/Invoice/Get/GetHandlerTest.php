<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Invoice\Get;

use N1ebieski\KSEFClient\Requests\Online\Invoice\Get\GetResponse;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Get\GetRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Get\GetResponseFixture;

final class GetHandlerTest extends AbstractTestCase
{
    public function testValidResponse(): void
    {
        $requestFixture = new GetRequestFixture();
        $responseFixture = new GetResponseFixture();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->invoice()->get($requestFixture->data);

        $this->assertInstanceOf(GetResponse::class, $response);

        $this->assertFixture($responseFixture->data, $response);
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
