<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Resources\Online\Session\Requests\Handlers;

use N1ebieski\KSEFClient\HttpClient\Exceptions\BadRequestException;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Testing\Concerns\HasClientMock;
use N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Session\Requests\AuthorisationChallengeRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Resources\Online\Session\Requests\Responses\AuthorisationChallengeResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Resources\Responses\ErrorResponseFixture;
use PHPUnit\Framework\TestCase;

final class AuthorisationChallengeHandlerTest extends TestCase
{
    use HasClientMock;

    public function testValidResponse(): void
    {
        $requestFixture = new AuthorisationChallengeRequestFixture();
        $responseFixture = new AuthorisationChallengeResponseFixture();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->session()->authorisationChallenge($requestFixture->data);

        $this->assertInstanceOf(AuthorisationChallengeResponse::class, $response);

        $this->assertObjectHasProperty('timestamp', $response);
        //@phpstan-ignore-next-line
        $this->assertEquals($responseFixture->contents['timestamp'], $response->timestamp->format('Y-m-d\TH:i:sP'));

        $this->assertObjectHasProperty('challenge', $response);
        $this->assertEquals($responseFixture->contents['challenge'], $response->challenge->value);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new AuthorisationChallengeRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->expectExceptionObject(new BadRequestException(
            //@phpstan-ignore-next-line
            message: $responseFixture->contents['exception']['exceptionDetailList'][0]['exceptionDescription'],
            //@phpstan-ignore-next-line
            code: $responseFixture->contents['exception']['exceptionDetailList'][0]['exceptionCode'],
            context: $responseFixture->getDataAsContext()
        ));

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->session()->authorisationChallenge($requestFixture->data);
    }
}
