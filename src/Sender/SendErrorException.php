<?php

namespace WScore\MailQueue\Sender;

use WScore\MailQueue\Mail\MailStatus;

class SendErrorException extends \RuntimeException
{
    private $mailStatus = MailStatus::FAILED;

    public static function failed($message = null): self
    {
        $e = new self($message??'failed to send mail');
        $e->mailStatus = MailStatus::FAILED;
        return $e;
    }

    public static function bounced($message = null): self
    {
        $e = new self($message??'mail bounced');
        $e->mailStatus = MailStatus::BOUNCED;
        return $e;
    }

    public function getMailStatus(): string
    {
        return $this->mailStatus;
    }
}