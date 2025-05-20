<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Session\AuthorisationChallenge;

use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class AuthorisationChallengeHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{AuthorisationChallengeRequestFixture, AuthorisationChallengeResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new AuthorisationChallengeRequestFixture(),
        ];

        $responses = [
            new AuthorisationChallengeResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{AuthorisationChallengeRequestFixture, AuthorisationChallengeResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(AuthorisationChallengeRequestFixture $requestFixture, AuthorisationChallengeResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->session()->authorisationChallenge($requestFixture->data)->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new AuthorisationChallengeRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->session()->authorisationChallenge($requestFixture->data);
    }
}
