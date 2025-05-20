<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Session\Status;

use N1ebieski\KSEFClient\Requests\Online\Session\Status\StatusRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\Status\Status\StatusRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\Status\Status\StatusResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class StatusHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{StatusRequestFixture, StatusResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new StatusRequestFixture(),
            new StatusRequestFixture()->withoutReferenceNumber()->withName('without reference number'),
            new StatusRequestFixture()->withoutPageSize()->withName('without page size'),
            new StatusRequestFixture()->withoutPageOffset()->withName('without page offset'),
            new StatusRequestFixture()->withoutIncludeDetails()->withName('without include details'),
        ];

        $responses = [
            new StatusResponseFixture(),
            new StatusResponseFixture()->withoutInvoiceStatusList()->withName('without invoice status list'),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{StatusRequestFixture, StatusResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(StatusRequestFixture $requestFixture, StatusResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = StatusRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->online()->session()->status($request)->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new StatusRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->session()->status($requestFixture->data);
    }
}
