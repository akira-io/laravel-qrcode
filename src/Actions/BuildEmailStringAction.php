<?php

declare(strict_types=1);

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\EmailData;

final class BuildEmailStringAction
{
    private const string PREFIX = 'mailto:';

    public function handle(EmailData $data): string
    {
        $params = array_filter([
            'subject' => $data->subject,
            'body' => $data->body,
            'cc' => $data->cc,
            'bcc' => $data->bcc,
        ]);

        if ($params === []) {
            return self::PREFIX.$data->address;
        }

        return self::PREFIX.$data->address.'?'.http_build_query($params);
    }
}
