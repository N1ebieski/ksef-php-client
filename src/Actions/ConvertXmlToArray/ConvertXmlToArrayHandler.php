<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\ConvertXmlToArray;

use LibXMLError;
use N1ebieski\KSEFClient\Actions\AbstractHandler;
use N1ebieski\KSEFClient\Actions\NormalizeXml\RemoveNamespaceFromXml\RemoveNamespaceFromXmlAction;
use N1ebieski\KSEFClient\Actions\NormalizeXml\RemoveNamespaceFromXml\RemoveNamespaceFromXmlHandler;
use N1ebieski\KSEFClient\Support\Arr;
use RuntimeException;
use SimpleXMLElement;

final class ConvertXmlToArrayHandler extends AbstractHandler
{
    public function __construct(
        private readonly RemoveNamespaceFromXmlHandler $removeNamespaceFromXmlHandler
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function handle(ConvertXmlToArrayAction $action): array
    {
        $useInternalErrors = libxml_use_internal_errors(true);

        try {
            $normalizedXml = $this->removeNamespaceFromXmlHandler->handle(
                new RemoveNamespaceFromXmlAction($action->xml)
            );

            $element = simplexml_load_string($normalizedXml, SimpleXMLElement::class, LIBXML_NOCDATA);

            if ($element === false) {
                $errors = array_map(
                    //@phpstan-ignore-next-line return.type
                    static fn (LibXMLError $error): string => mb_trim($error->message),
                    libxml_get_errors()
                );

                throw new RuntimeException(
                    sprintf('Invalid XML provided: %s', implode('; ', $errors))
                );
            }

            $mappedElement = Arr::mapRecursive((array) $element, $this->mapEmptyXmlElements(...));

            /** @var non-empty-string $encodedXml */
            $encodedXml = json_encode($mappedElement, JSON_THROW_ON_ERROR);

            /** @var array<string, mixed>|null $decodedXml */
            $decodedXml = json_decode($encodedXml, true, flags: JSON_THROW_ON_ERROR);

            if ( ! is_array($decodedXml)) {
                throw new RuntimeException('Failed to decode JSON to array');
            }

            return $decodedXml;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($useInternalErrors);
        }
    }

    private function mapEmptyXmlElements(mixed $value): mixed
    {
        if ( ! $value instanceof SimpleXMLElement) {
            return $value;
        }

        if ($value->count() === 0 && (string) $value === '') {
            return '';
        }

        return Arr::mapRecursive((array) $value, $this->mapEmptyXmlElements(...));
    }
}
