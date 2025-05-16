<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Online\Session\AuthorisationChallenge;

use N1ebieski\KSEFClient\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\AuthorisationChallenge\AuthorisationChallengeResponseFixture;

final class AuthorisationChallengeHandlerTest extends AbstractTestCase
{
    public function testValidResponse(): void
    {
        $requestFixture = new AuthorisationChallengeRequestFixture();
        $responseFixture = new AuthorisationChallengeResponseFixture();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->session()->authorisationChallenge($requestFixture->data);

        $this->assertInstanceOf(AuthorisationChallengeResponse::class, $response);

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
