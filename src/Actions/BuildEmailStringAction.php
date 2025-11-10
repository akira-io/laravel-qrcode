<?php

namespace Akira\QrCode\Actions;

use Akira\QrCode\ValueObjects\EmailData;

class BuildEmailStringAction
{
    private const PREFIX = 'mailto:';

    public function handle(EmailData $data): string
    {
        $params = array_filter([
            'subject' => $data->subject,
            'body' => $data->body,
            'cc' => $data->cc,
            'bcc' => $data->bcc,
        ]);

        if (empty($params)) {
            return self::PREFIX . $data->address;
        }

        return self::PREFIX . $data->address . '?' . http_build_query($params);
    }
}
