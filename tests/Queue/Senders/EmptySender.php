<?php

use WScore\MailQueue\Mail\MailData;
use WScore\MailQueue\Sender\SenderInterface;

class EmptySender implements SenderInterface
{
    public $mails = [];

    public function send(MailData $mailData): bool
    {
        $this->mails[] = $mailData;
        return true;
    }

    /**
     * @return MailData[]
     */
    public function getMails(): array
    {
        return $this->mails;
    }
}