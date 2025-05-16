<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Invoice\Status;

use N1ebieski\KSEFClient\Requests\Online\Invoice\Status\StatusResponse;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Status\StatusRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Status\StatusResponseFixture;

final class StatusHandlerTest extends AbstractTestCase
{
    public function testValidResponseWithEmptyInvoiceStatus(): void
    {
        $requestFixture = new StatusRequestFixture();
        $responseFixture = new StatusResponseFixture()->withEmptyInvoiceStatus();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->invoice()->status($requestFixture->data);

        $this->assertInstanceOf(StatusResponse::class, $response);

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testValidResponseWithInvoiceStatus(): void
    {
        $requestFixture = new StatusRequestFixture();
        $responseFixture = new StatusResponseFixture()->withInvoiceStatus();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->invoice()->status($requestFixture->data);

        $this->assertInstanceOf(StatusResponse::class, $response);

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new StatusRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->invoice()->status($requestFixture->data);
    }
}
