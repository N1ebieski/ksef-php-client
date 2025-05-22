<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Invoice\Send;

use N1ebieski\KSEFClient\Requests\Online\Invoice\Send\SendRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\AbstractSendRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\SendFakturaKorygujacaDaneNabywcyRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\SendFakturaKorygujacaUniwersalnaRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\SendFakturaSprzedazyTowaruRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send\SendResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class SendHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{AbstractSendRequestFixture, SendResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new SendFakturaSprzedazyTowaruRequestFixture(),
            new SendFakturaKorygujacaUniwersalnaRequestFixture(),
            new SendFakturaKorygujacaDaneNabywcyRequestFixture(),
        ];

        $responses = [
            new SendResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(AbstractSendRequestFixture $requestFixture, SendResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = SendRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->online()->invoice()->send($requestFixture->data)->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new SendFakturaSprzedazyTowaruRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->invoice()->send($requestFixture->data);
    }
}
