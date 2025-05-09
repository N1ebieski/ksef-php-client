<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Online\Invoice\Requests;

use DateTimeImmutable;
use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Adres;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Fa;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Naglowek;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Platnosc;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Podmiot1;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Podmiot2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\Stopka;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\DTOs\WarunkiTransakcji;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\AdresL2;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\FP;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\NrKlienta;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_19N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_1M;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_22N;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\P_PMarzyN;
use N1ebieski\KSEFClient\Resources\Online\Invoice\Requests\ValueObjects\SystemInfo;
use N1ebieski\KSEFClient\Resources\Online\ValueObjects\ElementReferenceNumber;
use N1ebieski\KSEFClient\Resources\Request;
use RuntimeException;

final readonly class StatusRequest extends Request
{
    public function __construct(
        public ElementReferenceNumber $invoiceElementReferenceNumber
    ) {
    }
}
