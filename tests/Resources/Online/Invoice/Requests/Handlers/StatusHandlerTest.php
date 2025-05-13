<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Resources\Online\Invoice\Requests\Handlers;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\Responses\StatusResponse;
use N1ebieski\KSEFClient\Testing\Concerns\HasClientMock;
use N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Invoice\Requests\Responses\StatusResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Invoice\Requests\StatusRequestFixture;
use PHPUnit\Framework\TestCase;

final class StatusHandlerTest extends TestCase
{
    use HasClientMock;

    public function testValidResponseWithEmptyInvoiceStatus(): void
    {
        $requestFixture = new StatusRequestFixture();
        $responseFixture = new StatusResponseFixture()->withEmptyInvoiceStatus();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->invoice()->status($requestFixture->data);

        $this->assertInstanceOf(StatusResponse::class, $response);

        var_dump($response);

        // $this->assertObjectHasProperty('elementReferenceNumber', $response);
        // $this->assertEquals($responseFixture->contents['elementReferenceNumber'], $response->elementReferenceNumber->value);

        // $this->assertObjectHasProperty('referenceNumber', $response);
        // $this->assertEquals($responseFixture->contents['referenceNumber'], $response->referenceNumber->value);

        // $this->assertObjectHasProperty('timestamp', $response);
        // //@phpstan-ignore-next-line
        // $this->assertEquals($responseFixture->contents['timestamp'], $response->timestamp->format('Y-m-d\TH:i:sP'));

        // $this->assertObjectHasProperty('processingCode', $response);
        // $this->assertEquals($responseFixture->contents['processingCode'], $response->processingCode->value);

        // $this->assertObjectHasProperty('processingDescription', $response);
        // $this->assertEquals($responseFixture->contents['processingDescription'], $response->processingDescription->value);
    }

    // public function testInvalidResponse(): void
    // {
    //     $requestFixture = new SendRequestFixture();
    //     $responseFixture = new ErrorResponseFixture();

    //     $this->expectExceptionObject(new BadRequestException(
    //         //@phpstan-ignore-next-line
    //         message: $responseFixture->contents['exception']['exceptionDetailList'][0]['exceptionDescription'],
    //         //@phpstan-ignore-next-line
    //         code: $responseFixture->contents['exception']['exceptionDetailList'][0]['exceptionCode'],
    //         context: $responseFixture->getDataAsContext()
    //     ));

    //     $clientStub = $this->getClientStub($responseFixture);

    //     $clientStub->online()->invoice()->send($requestFixture->data);
    // }
}
