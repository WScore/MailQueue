<?php
declare(strict_types=1);

namespace WScore\MailQueue\Sender;

use WScore\MailQueue\Mail\MailData;

interface SenderInterface
{
    /**
     * @param MailData $mailData
     * @return bool
     * @throws SendErrorException
     */
    public function send(MailData $mailData): bool;
}