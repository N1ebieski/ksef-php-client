<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests;

use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Fa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Naglowek;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Podmiot1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Podmiot2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Stopka;
use N1ebieski\KSEFClient\Resources\Request;

final readonly class SendRequest extends Request
{
    /**
     * @param Podmiot1 $podmiot1 Dane podatnika. Imię i nazwisko lub nazwa sprzedawcy towarów lub usług
     * @param Podmiot2 $podmiot2 Dane nabywcy
     * @param Fa $fa Na podstawie art. 106a - 106q ustawy. Pola dotyczące wartości sprzedaży i podatku wypełnia się w walucie, w której wystawiono fakturę, z wyjątkiem pól dotyczących podatku przeliczonego zgodnie z przepisami Działu VI w związku z art. 106e ust. 11 ustawy. W przypadku wystawienia faktury korygującej, wypełnia się wszystkie pola wg stanu po korekcie, a pola dotyczące podstaw opodatkowania, podatku oraz należności ogółem wypełnia się poprzez różnicę
     * @param null|Stopka $stopka Pozostałe dane na fakturze
     * @return void
     */
    public function __construct(
        public Naglowek $naglowek,
        public Podmiot1 $podmiot1,
        public Podmiot2 $podmiot2,
        public Fa $fa,
        public ?Stopka $stopka = null
    ) {
    }
}
