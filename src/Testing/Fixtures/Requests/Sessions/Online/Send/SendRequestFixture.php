<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Send;

use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\AbstractFakturaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaRR\AbstractFakturaFixture as AbstractFakturaRRFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

final class SendRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'referenceNumber' => '20250625-EE-319D7EE000-B67F415CDC-2C',
    ];

    public function withFakturaFixture(AbstractFakturaFixture | AbstractFakturaRRFixture $faktura): self
    {
        $this->data['faktura'] = $faktura->data;

        return $this;
    }

    public function withFormCode(FormCode $formCode): self
    {
        $this->data['formCode'] = $formCode->value;

        return $this;
    }
}
