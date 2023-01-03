<?php
declare(strict_types=1);

namespace WScore\MailQueue\Queue;

use PDO;
use WScore\MailQueue\Mail\MailStatus;
use WScore\MailQueue\Sender\SenderInterface;
use WScore\MailQueue\Sender\SendErrorException;

class SendQueue
{
    /**
     * @var QueueDba
     */
    private $dba;

    /**
     * @var SenderInterface
     */
    private $sender;

    public function __construct(QueueDba $dba)
    {
        $this->dba = $dba;
    }

    public static function forgeWithSender(SenderInterface $sender, PDO $pdo, $table = 'mail_queue'): SendQueue
    {
        $self =new self(new QueueDba($pdo, $table));
        $self->setSender($sender);
        return $self;
    }

    public function setSender(SenderInterface $sender)
    {
        $this->sender = $sender;
    }

    public function sendQueId(string $que_id)
    {
        $list = $this->dba->listByQueId($que_id);
        foreach ($list as $mailData) {
            if ($mailData->getStatus() === MailStatus::READY) {
                try {
                    if ($this->sender->send($mailData)) {
                        $this->dba->success($mailData);
                    } else {
                        $this->dba->failed($mailData);
                    }
                } catch (SendErrorException $e) {
                    $this->dba->updateStatus($mailData->getMailId(), $e->getMailStatus(), $e->getMessage());
                }
            }
        }
    }
}