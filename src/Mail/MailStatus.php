<?php
declare(strict_types=1);

namespace WScore\MailQueue\Mail;

class MailStatus
{
    const READY = 'READY';
    const SENT = 'SENT';
    const FAILED = 'FAILED';
    const BOUNCED = 'BOUNCED';
}