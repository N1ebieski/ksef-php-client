<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Common\Status;

use N1ebieski\KSEFClient\Requests\Common\Status\StatusRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Common\Status\StatusRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Common\Status\StatusResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
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
        ];

        $responses = [
            new StatusResponseFixture(),
            new StatusResponseFixture()->withoutUpo()->withName('without upo'),
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

        $response = $clientStub->common()->status($request)->object();

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
