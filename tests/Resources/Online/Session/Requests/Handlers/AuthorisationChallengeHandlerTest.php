<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Resources\Online\Session\Requests\Handlers;

use DateTimeImmutable;
use N1ebieski\KSEFClient\HttpClient\Exceptions\BadRequestException;
use N1ebieski\KSEFClient\Resources\Online\Session\Requests\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Testing\Concerns\HasClientMock;
use N1ebieski\KSEFClient\Testing\Fixtures\Responses\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Responses\Online\Session\AuthorisationChallengeValidResponseFixture;
use PHPUnit\Framework\TestCase;

final class AuthorisationChallengeHandlerTest extends TestCase
{
    use HasClientMock;

    public function testValidResponse(): void
    {
        $responseFixture = new AuthorisationChallengeValidResponseFixture();

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->session()->authorisationChallenge([
            'nip' => '1111111111',
        ]);

        $this->assertInstanceOf(AuthorisationChallengeResponse::class, $response);

        $this->assertObjectHasProperty('timestamp', $response);
        //@phpstan-ignore-next-line
        $this->assertEquals(new DateTimeImmutable($responseFixture->contents['timestamp']), $response->timestamp);

        $this->assertObjectHasProperty('challenge', $response);
        $this->assertEquals($responseFixture->contents['challenge'], $response->challenge);
    }

    public function testInvalidResponse(): void
    {
        $responseFixture = new ErrorResponseFixture();

        $this->expectExceptionObject(new BadRequestException(
            //@phpstan-ignore-next-line
            message: $responseFixture->contents['exception']['exceptionDetailList'][0]['exceptionDescription'],
            //@phpstan-ignore-next-line
            code: $responseFixture->contents['exception']['exceptionDetailList'][0]['exceptionCode'],
            context: $responseFixture->getDataAsContext()
        ));

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->online()->session()->authorisationChallenge([
            'nip' => '1111111111',
        ]);
    }
}
