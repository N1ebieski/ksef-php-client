<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\GeneratePDF;

use DateTimeInterface;
use N1ebieski\KSEFClient\Actions\AbstractHandler;
use N1ebieski\KSEFClient\DTOs\KsefPDFs;
use N1ebieski\KSEFClient\ValueObjects\QRCode;
use N1ebieski\KSEFClient\ValueObjects\Requests\KsefNumber;
use RuntimeException;

final class GeneratePDFHandler extends AbstractHandler
{
    public function handle(GeneratePDFAction $action): KsefPDFs
    {
        $documents = array_filter([
            'invoice' => $action->invoiceDocument,
            'upo' => $action->upoDocument,
            'confirmation' => $action->confirmationDocument
        ]);

        $pdfs = [];

        foreach ($documents as $key => $document) {
            $xmlFile = tempnam(sys_get_temp_dir(), 'xml_');

            if ($xmlFile === false) {
                throw new RuntimeException(
                    sprintf('Unable to create temp file for xml in %s.', sys_get_temp_dir())
                );
            }

            $pdfFile = tempnam(sys_get_temp_dir(), 'pdf_');

            if ($pdfFile === false) {
                throw new RuntimeException(
                    sprintf('Unable to create temp file for pdf in %s.', sys_get_temp_dir())
                );
            }

            file_put_contents($xmlFile, $document);

            $command = $this->getCommand($action, $key, $xmlFile, $pdfFile);

            $process = proc_open(
                $command,
                [
                    ["pipe", "r"],  // stdin
                    ["pipe", "w"],  // stdout
                    ["pipe", "w"],  // stderr
                ],
                $pipes
            );

            if ( ! is_resource($process)) {
                throw new RuntimeException('Unable to start Node.js process.');
            }

            fclose($pipes[0]);

            $stderr = stream_get_contents($pipes[2]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            $returnVar = proc_close($process);

            if ($returnVar !== 0) {
                throw new RuntimeException("Node.js process exited with code {$returnVar}:\n{$stderr}");
            }

            $content = file_get_contents($pdfFile);

            if ($content === false) {
                throw new RuntimeException(
                    sprintf('Unable to read PDF file "%s" for document "%s".', $pdfFile, $key)
                );
            }

            $pdfs[$key] = $content;

            unlink($xmlFile);
            unlink($pdfFile);
        }

        /** @var array<string, non-empty-string> $pdfs */
        return new KsefPDFs(...$pdfs);
    }

    private function getCommand(GeneratePDFAction $action, string $key, string $xmlFile, string $pdfFile): string
    {
        $command = "{$action->nodePath} {$action->ksefFeInvoiceConverterPath->value} {$key} {$xmlFile} {$pdfFile}";

        if ( ! in_array($key, ['invoice', 'confirmation'], true)) {
            return $command;
        }

        if ($action->qrCodes?->code1 instanceof QRCode) {
            $command .= " --qr-code {$action->qrCodes->code1->url}";
        }

        if ($action->qrCodes?->code2 instanceof QRCode) {
            $command .= " --qr-code2 {$action->qrCodes->code2->url}";
        }

        if ($action->watermark !== null) {
            $command .= " --watermark \"{$action->watermark}\"";
        }

        if ($key !== 'invoice') {
            return $command;
        }

        if ($action->ksefNumber instanceof KsefNumber) {
            $command .= " --nr-ksef {$action->ksefNumber->value}";
        }

        if ($action->acquisitionDate instanceof DateTimeInterface) {
            $command .= " --ac-date {$action->acquisitionDate->format('d.m.Y')}";
        }

        return $command;
    }
}
