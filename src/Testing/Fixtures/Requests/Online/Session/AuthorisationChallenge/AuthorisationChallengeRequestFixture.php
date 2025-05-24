<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Session\AuthorisationChallenge;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class AuthorisationChallengeRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'contextIdentifier' => [
            'subjectIdentifierByGroup' => [
                'subjectIdentifierByCompany' => '1111111111'
            ]
        ]
    ];
}
