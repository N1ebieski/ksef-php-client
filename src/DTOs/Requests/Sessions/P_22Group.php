<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Contracts\XmlNormalizableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Arr;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_22;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\P_42_5;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class P_22Group extends AbstractDTO implements DomSerializableInterface, XmlNormalizableInterface
{
    /**
     * @var array<int, NowySrodekTransportu>
     */
    public readonly array $nowySrodekTransportu;

    /**
     * @param P_22 $p_22 Znacznik wewnątrzwspólnotowej dostawy nowych środków transportu
     * @param P_42_5 $p_42_5 Jeśli występuje obowiązek, o którym mowa w art. 42 ust. 5 ustawy
     * @param array<int, NowySrodekTransportu> $nowySrodekTransportu
     */
    public function __construct(
        public readonly P_42_5 $p_42_5,
        array $nowySrodekTransportu,
        public readonly P_22 $p_22 = P_22::Default,
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

        $p_22Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22Group');
        $dom->appendChild($p_22Group);

        $p_22 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22');
        $p_22->appendChild($dom->createTextNode((string) $this->p_22->value));

        $p_22Group->appendChild($p_22);

        $p_42_5 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_42_5');
        $p_42_5->appendChild($dom->createTextNode((string) $this->p_42_5->value));

        $p_22Group->appendChild($p_42_5);

        foreach ($this->nowySrodekTransportu as $nowySrodekTransportu) {
            $nowySrodekTransportu = $dom->importNode($nowySrodekTransportu->toDom()->documentElement, true);

            $p_22Group->appendChild($nowySrodekTransportu);
        }

        return $dom;
    }

    public static function normalizeXmlArray(array $data): array
    {
        $data['NowySrodekTransportu'] = array_map(
            NowySrodekTransportu::normalizeXmlArray(...),
            Arr::ensureList($data['NowySrodekTransportu'])
        );

        return Arr::only($data, ['P_22', 'P_42_5', 'NowySrodekTransportu']);
    }
}
