<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\VCardData;

final class BuildVCardStringAction
{
    public function handle(VCardData $data): string
    {
        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'FN:'.$this->escape($data->fullName),
        ];

        if ($data->hasNameParts()) {
            $lines[] = 'N:'.$this->escape((string) $data->lastName).';'.$this->escape((string) $data->firstName).';;;';
        }

        $this->appendOptionalLine($lines, 'ORG', $data->organization);
        $this->appendOptionalLine($lines, 'TITLE', $data->title);
        $this->appendOptionalLine($lines, 'TEL', $data->phone);
        $this->appendOptionalLine($lines, 'EMAIL', $data->email);
        $this->appendOptionalLine($lines, 'URL', $data->url);
        $this->appendAddressLine($lines, $data->address);
        $this->appendOptionalLine($lines, 'NOTE', $data->note);

        $lines[] = 'END:VCARD';

        return implode("\n", $lines);
    }

    /**
     * @param  array<int, string>  $lines
     */
    private function appendOptionalLine(array &$lines, string $name, ?string $value): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $lines[] = $name.':'.$this->escape($value);
    }

    /**
     * @param  array<int, string>  $lines
     */
    private function appendAddressLine(array &$lines, ?string $address): void
    {
        if ($address === null || $address === '') {
            return;
        }

        $lines[] = 'ADR:;;'.$this->escape($address).';;;;';
    }

    private function escape(string $value): string
    {
        return strtr($value, [
            '\\' => '\\\\',
            "\n" => '\n',
            "\r" => '',
            ';' => '\;',
            ',' => '\,',
        ]);
    }
}
