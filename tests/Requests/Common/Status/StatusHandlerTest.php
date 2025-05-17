<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Common\Status;

use N1ebieski\KSEFClient\Requests\Common\Status\StatusResponse;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Common\Status\StatusRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Common\Status\StatusResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;

final class StatusHandlerTest extends AbstractTestCase
{
    public function testValidResponseWithoutUpo(): void
    {
        $requestFixture = new StatusRequestFixture();
        $responseFixture = new StatusResponseFixture()->withoutUpo();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->common()->status($requestFixture->data);

        $this->assertInstanceOf(StatusResponse::class, $response);

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testValidResponseWithUpo(): void
    {
        $requestFixture = new StatusRequestFixture();
        $responseFixture = new StatusResponseFixture()->withUpo();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->common()->status($requestFixture->data);

        $this->assertInstanceOf(StatusResponse::class, $response);

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new StatusRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->common()->status($requestFixture->data);
    }
}
