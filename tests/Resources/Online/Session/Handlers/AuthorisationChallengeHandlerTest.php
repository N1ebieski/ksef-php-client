<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Resources\Online\Session\Handlers;

use DateTimeImmutable;
use N1ebieski\KSEFClient\Resources\Online\Session\Responses\AuthorisationChallengeResponse;
use N1ebieski\KSEFClient\Testing\Concerns\HasClientMock;
use N1ebieski\KSEFClient\Testing\Fixtures\AuthorisationChallengeResponseFixture;
use PHPUnit\Framework\TestCase;

final class AuthorisationChallengeHandlerTest extends TestCase
{
    use HasClientMock;

    public function testHandle(): void
    {
        $responseFixture = new AuthorisationChallengeResponseFixture()->data;

        $clientStub = $this->getClientStub($responseFixture);

        $response = $clientStub->online()->session()->authorisationChallenge([
            'nip' => '5832908528',
        ]);

        $this->assertInstanceOf(AuthorisationChallengeResponse::class, $response);

        $this->assertObjectHasProperty('timestamp', $response);
        //@phpstan-ignore-next-line
        $this->assertEquals(new DateTimeImmutable($responseFixture['timestamp']), $response->timestamp);

        $this->assertObjectHasProperty('challenge', $response);
        $this->assertEquals($responseFixture['challenge'], $response->challenge);
    }
}
