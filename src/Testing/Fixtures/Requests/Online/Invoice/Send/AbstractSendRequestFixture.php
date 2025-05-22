<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\Requests\Online\Invoice\Send;

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

abstract class AbstractSendRequestFixture extends AbstractRequestFixture
{
    public function withNIP(string $nip): self
    {
        $this->data['podmiot1']['daneIdentyfikacyjne']['nip'] = $nip;

        return $this;
    }
}
