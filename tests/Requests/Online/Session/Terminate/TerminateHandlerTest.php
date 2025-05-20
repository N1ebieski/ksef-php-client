<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Session\Terminate;

use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\Terminate\TerminateResponseFixture;

final class TerminateHandlerTest extends AbstractTestCase
{
    public function testValidResponse(): void
    {
        $responseFixture = new TerminateResponseFixture();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->session()->terminate()->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->session()->terminate();
    }
}
