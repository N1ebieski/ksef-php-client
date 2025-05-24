<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Invoice\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_22;
use N1ebieski\KSEFClient\Requests\Online\Invoice\ValueObjects\P_42_5;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;

final readonly class P_22Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var array<int, NowySrodekTransportu>
     */
    public array $nowySrodekTransportu;

    /**
     * @param P_22 $p_22 Znacznik wewnątrzwspólnotowej dostawy nowych środków transportu
     * @param P_42_5 $p_42_5 Jeśli występuje obowiązek, o którym mowa w art. 42 ust. 5 ustawy, należy podać wartość "1", w przeciwnym przypadku - wartość "2
     * @param array<int, NowySrodekTransportu> $nowySrodekTransportu
     * @return void
     */
    public function __construct(
        public P_42_5 $p_42_5,
        array $nowySrodekTransportu,
        public P_22 $p_22 = P_22::Default,
    ) {
        Validator::validate([
            'nowySrodekTransportu' => $nowySrodekTransportu
        ], [
            'nowySrodekTransportu' => [new MinRule(1), new MaxRule(10000)]
        ]);

        $this->nowySrodekTransportu = $nowySrodekTransportu;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22Group = $dom->createElement('P_22Group');
        $dom->appendChild($p_22Group);

        $p_22 = $dom->createElement('P_22');
        $p_22->appendChild($dom->createTextNode((string) $this->p_22->value));

        $p_22Group->appendChild($p_22);

        $p_42_5 = $dom->createElement('P_42_5');
        $p_42_5->appendChild($dom->createTextNode((string) $this->p_42_5->value));

        $p_22Group->appendChild($p_42_5);

        foreach ($this->nowySrodekTransportu as $nowySrodekTransportu) {
            $nowySrodekTransportu = $dom->importNode($nowySrodekTransportu->toDom()->documentElement, true);

            $p_22Group->appendChild($nowySrodekTransportu);
        }

        return $dom;
    }
}
