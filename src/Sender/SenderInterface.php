<?php
declare(strict_types=1);

namespace WScore\MailQueue\Sender;

use WScore\MailQueue\Mail\MailData;

interface SenderInterface
{
    public function send(MailData $mailData): bool;
}