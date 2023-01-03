<?php

use WScore\MailQueue\Mail\MailData;
use WScore\MailQueue\Sender\SenderInterface;
use WScore\MailQueue\Sender\SendErrorException;

class EmptySender implements SenderInterface
{
    public $mails = [];

    public function send(MailData $mailData): bool
    {
        if ($mailData->getSubject() === 'throw e') {
            throw SendErrorException::failed('thrown an exception');
        }
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