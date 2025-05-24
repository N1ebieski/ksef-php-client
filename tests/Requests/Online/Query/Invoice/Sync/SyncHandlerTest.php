<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Query\Invoice\Sync;

use N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Sync\SyncRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Sync\SyncRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Query\Invoice\Sync\SyncResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class SyncHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{SyncRequestFixture, SyncResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new SyncRequestFixture()->withRange('-2 weeks'),
            new SyncRequestFixture()->withDetail(),
        ];

        $responses = [
            new SyncResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{SyncRequestFixture, SyncResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(SyncRequestFixture $requestFixture, SyncResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = SyncRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->online()->query()->invoice()->sync($request)->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new SyncRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->invoice()->send($requestFixture->data);
    }
}
