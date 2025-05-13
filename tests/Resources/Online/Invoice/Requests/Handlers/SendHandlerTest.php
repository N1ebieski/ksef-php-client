<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Resources\Online\Invoice\Requests\Handlers;

use N1ebieski\KSEFClient\HttpClient\Exceptions\BadRequestException;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\SendResponse;
use N1ebieski\KSEFClient\Testing\Concerns\HasClientMock;
use N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Invoice\Requests\Responses\SendResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Invoice\Requests\SendRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Resources\Responses\ErrorResponseFixture;
use PHPUnit\Framework\TestCase;

final class SendHandlerTest extends TestCase
{
    use HasClientMock;

    public function testValidResponse(): void
    {
        $requestFixture = new SendRequestFixture();
        $responseFixture = new SendResponseFixture();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->invoice()->send($requestFixture->data);

        $this->assertInstanceOf(SendResponse::class, $response);

        $this->assertObjectHasProperty('elementReferenceNumber', $response);
        $this->assertEquals($responseFixture->contents['elementReferenceNumber'], $response->elementReferenceNumber->value);

        $this->assertObjectHasProperty('referenceNumber', $response);
        $this->assertEquals($responseFixture->contents['referenceNumber'], $response->referenceNumber->value);

        $this->assertObjectHasProperty('timestamp', $response);
        $this->assertEquals($responseFixture->contents['timestamp'], $response->timestamp->format('Y-m-d\TH:i:sP'));

        $this->assertObjectHasProperty('processingCode', $response);
        $this->assertEquals($responseFixture->contents['processingCode'], $response->processingCode->value);

        $this->assertObjectHasProperty('processingDescription', $response);
        $this->assertEquals($responseFixture->contents['processingDescription'], $response->processingDescription->value);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new SendRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->expectExceptionObject(new BadRequestException(
            //@phpstan-ignore-next-line
            message: $responseFixture->contents['exception']['exceptionDetailList'][0]['exceptionDescription'],
            //@phpstan-ignore-next-line
            code: $responseFixture->contents['exception']['exceptionDetailList'][0]['exceptionCode'],
            context: $responseFixture->getDataAsContext()
        ));

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->invoice()->send($requestFixture->data);
    }
}
