<?php

declare(strict_types=1);

namespace Akira\QrCode\Commands;

use Akira\QrCode\QrCode;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Throwable;

final class GenerateQrCodeCommand extends Command
{
    protected $signature = 'qrcode:generate
        {text? : Text payload to encode}
        {--output= : Output file path}
        {--format=png : Output format}
        {--size=200 : QR code size}
        {--margin=4 : QR code margin}
        {--error-correction=H : Error correction level}
        {--batch= : CSV file with text and output columns}';

    protected $description = 'Generate QR code files from terminal input.';

    public function handle(QrCode $qrCode): int
    {
        try {
            $batchPath = $this->option('batch');

            if (is_string($batchPath) && $batchPath !== '') {
                return $this->generateBatch($qrCode, $batchPath);
            }

            $text = $this->argument('text');
            $output = $this->stringOption('output');

            throw_unless(is_string($text) && $text !== '', InvalidArgumentException::class, 'Text is required unless --batch is used.');
            throw_if($output === '', InvalidArgumentException::class, 'The --output option is required.');

            $this->writeQrCode($qrCode, $text, $output);

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    private function generateBatch(QrCode $qrCode, string $batchPath): int
    {
        throw_unless(is_readable($batchPath), InvalidArgumentException::class, "Batch file is not readable: {$batchPath}");

        $handle = fopen($batchPath, 'r');

        throw_if($handle === false, InvalidArgumentException::class, "Batch file is not readable: {$batchPath}");

        $generatedCount = 0;

        try {
            while (($row = fgetcsv($handle, escape: '\\')) !== false) {
                $generatedCount++;
                $text = $row[0] ?? null;
                $output = $row[1] ?? null;

                throw_unless(is_string($text) && $text !== '', InvalidArgumentException::class, "Batch row {$generatedCount} is missing text.");
                throw_unless(is_string($output) && $output !== '', InvalidArgumentException::class, "Batch row {$generatedCount} is missing output.");

                $this->writeQrCode(clone $qrCode, $text, $output);
            }
        } finally {
            fclose($handle);
        }

        $this->components->info("Generated {$generatedCount} QR code file(s).");

        return self::SUCCESS;
    }

    private function writeQrCode(QrCode $qrCode, string $text, string $output): void
    {
        $directory = dirname($output);

        throw_unless(is_dir($directory) && is_writable($directory), InvalidArgumentException::class, "Output directory is not writable: {$directory}");

        $qrCode
            ->format($this->stringOption('format', 'png'))
            ->size($this->intOption('size'))
            ->margin($this->intOption('margin'))
            ->errorCorrection($this->stringOption('error-correction', 'H'))
            ->generate($text, $output);

        $this->components->info("QR code written to [{$output}].");
    }

    private function stringOption(string $name, string $default = ''): string
    {
        $value = $this->option($name);

        return is_string($value) ? $value : $default;
    }

    private function intOption(string $name): int
    {
        $value = $this->option($name);

        throw_unless(is_numeric($value), InvalidArgumentException::class, "The --{$name} option must be numeric.");

        return (int) $value;
    }
}
