<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\NormalizeXml\AddNamespaceToXml;

use DOMCdataSection;
use DOMComment;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMProcessingInstruction;
use DOMText;
use LibXMLError;
use N1ebieski\KSEFClient\Actions\AbstractHandler;
use RuntimeException;

final class AddNamespaceToXmlHandler extends AbstractHandler
{
    public function handle(AddNamespaceToXmlAction $action): string
    {
        libxml_use_internal_errors(true);

        try {
            $sourceDocument = new DOMDocument();

            $loaded = $sourceDocument->loadXML($action->xml);

            if ($loaded === false) {
                $errors = array_map(
                    static fn (LibXMLError $error): string => mb_trim($error->message), //@phpstan-ignore-line return.type
                    libxml_get_errors()
                );

                throw new RuntimeException(
                    sprintf('Invalid XML provided: %s', implode('; ', $errors))
                );
            }

            if ( ! $sourceDocument->documentElement instanceof DOMElement) {
                throw new RuntimeException('Empty XML document');
            }

            $targetDocument = new DOMDocument('1.0', 'UTF-8');
            $targetDocument->formatOutput = false;

            $root = $this->copyElementWithNamespace(
                $sourceDocument->documentElement,
                $targetDocument,
                $action->prefix,
                $action->namespaceUri
            );

            $targetDocument->appendChild($root);

            $xml = $targetDocument->saveXML();

            if ($xml === false) {
                throw new RuntimeException('Failed to save XML');
            }

            return $xml;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors(false);
        }
    }

    private function copyElementWithNamespace(
        DOMElement $source,
        DOMDocument $targetDocument,
        string $prefix,
        string $namespaceUri
    ): DOMElement {
        $localName = $source->localName;

        if ($localName === null) {
            throw new RuntimeException('Local name of the element is null');
        }

        $newElement = $targetDocument->createElementNS($namespaceUri, sprintf('%s:%s', $prefix, $localName));

        foreach ($source->attributes as $attribute) {
            $newElement->setAttribute($attribute->name, $attribute->value);
        }

        $child = $source->firstChild;

        while ($child !== null) {
            $node = $this->copyNode($child, $targetDocument, $prefix, $namespaceUri);

            if ($node === false) {
                throw new RuntimeException('Failed to copy XML node');
            }

            $newElement->appendChild($node);

            $child = $child->nextSibling;
        }

        return $newElement;
    }

    private function copyNode(
        DOMNode $node,
        DOMDocument $document,
        string $prefix,
        string $namespaceUri
    ): DOMNode | false {
        return match (true) {
            $node instanceof DOMCdataSection => $document->createCDATASection($node->data),

            $node instanceof DOMText => $document->createTextNode($node->nodeValue), // @phpstan-ignore-line

            $node instanceof DOMComment => $document->createComment($node->data),

            $node instanceof DOMProcessingInstruction => $document->createProcessingInstruction(
                $node->target,
                $node->data
            ),

            $node instanceof DOMElement => $this->copyElementWithNamespace(
                $node,
                $document,
                $prefix,
                $namespaceUri
            ),

            default => throw new RuntimeException(
                sprintf('Unsupported XML node: %s', $node::class)
            )
        };
    }
}
