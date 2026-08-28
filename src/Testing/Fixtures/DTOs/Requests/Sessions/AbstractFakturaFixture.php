<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions;

use DateTimeImmutable;
use DateTimeInterface;
use N1ebieski\KSEFClient\Testing\Fixtures\AbstractFixture as BaseAbstractFixture;

/**
 * @property array<string, mixed> $data
 */
abstract class AbstractFakturaFixture extends BaseAbstractFixture
{
    public function withDate(DateTimeInterface | string $date): self
    {
        if ( ! $date instanceof DateTimeInterface) {
            $date = new DateTimeImmutable($date);
        }

        $this->data['fa']['p_1'] = $date->format('Y-m-d');

        if (isset($this->data['fa']['p_6Group']['p_6'])) {
            $this->data['fa']['p_6Group']['p_6'] = $date->format('Y-m-d');
        }

        return $this;
    }

    public function withTodayDate(): self
    {
        return $this->withDate(new DateTimeImmutable());
    }

    public function withInvoiceNumber(string $invoiceNumber): self
    {
        $this->data['fa']['p_2'] = $invoiceNumber;

        return $this;
    }

    public function withRandomInvoiceNumber(): self
    {
        return $this->withInvoiceNumber(strtoupper(uniqid("INV-")));
    }

    public function withNip(string $nip): self
    {
        $this->data['podmiot1']['daneIdentyfikacyjne']['nip'] = $nip;

        return $this;
    }

    public function withForNip(string $nip): self
    {
        $this->data['podmiot2']['daneIdentyfikacyjne']['idGroup']['nip'] = $nip;

        return $this;
    }

    public function withoutPayment(): self
    {
        unset($this->data['fa']['platnosc']);

        return $this;
    }
}
