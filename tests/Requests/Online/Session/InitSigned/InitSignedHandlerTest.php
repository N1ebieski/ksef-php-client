<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Session\InitSigned;

use InvalidArgumentException;
use N1ebieski\KSEFClient\Requests\Online\Session\InitSigned\InitSignedResponse;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitSigned\InitSignedRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\InitSigned\InitSignedResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class InitSignedHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{InitSignedRequestFixture, InitSignedResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new InitSignedRequestFixture(),
        ];

        $responses = [
            new InitSignedResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{InitSignedRequestFixture, InitSignedResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(InitSignedRequestFixture $requestFixture, InitSignedResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->session()->initSigned($requestFixture->data);

        $this->assertInstanceOf(InitSignedResponse::class, $response);

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testWhenRequestHasEmptyCertificatePath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Certificate path is required for this request');

        $clientStub = $this->getClientStub(new InitSignedResponseFixture());

        $clientStub->online()->session()->initSigned(new InitSignedRequestFixture()->withoutCertificatePath()->data);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new InitSignedRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->session()->initSigned($requestFixture->data);
    }
}
