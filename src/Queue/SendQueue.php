<?php

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
            if ($this->sender->send($mailData)) {
                $this->dba->success($mailData);
            } else {
                $this->dba->failed($mailData);
            }
        }
    }
}