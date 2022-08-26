<?php
declare(strict_types=1);

namespace WScore\MailQueue\Queue;

use DateTimeImmutable;
use DateTimeInterface;
use PDO;
use RuntimeException;
use WScore\MailQueue\Mail\MailData;
use WScore\MailQueue\Mail\MailStatus;
use WScore\MailQueue\Mail\MailToArray;

class SaveQueue
{
    private $que_id;
    /**
     * @var QueueDba
     */
    private $dba;
    /**
     * @var DateTimeImmutable
     */
    private $now;
    /**
     * @var MailToArray
     */
    private $converter;

    public function __construct(QueueDba $dba, DateTimeInterface $now = null)
    {
        $this->dba = $dba;
        $this->now = $now ?: new DateTimeImmutable('now');
        $this->converter = new MailToArray();
    }

    public static function forgeWithQueId(PDO $pdo, $table = 'mail_queue'): SaveQueue
    {
        $self =new self(new QueueDba($pdo, $table));
        $self->createQueId();
        return $self;
    }

    public function withQueId(string $que_id): SaveQueue
    {
        $this->que_id = $que_id;
        return $this;
    }

    private function createQueId()
    {
        foreach (range(0, 5) as $item) {
            $que_id = date('YmdHis-') . md5(uniqid());
            if ($this->dba->isQueIdUnique($que_id)) {
                $this->que_id = $que_id;
                return;
            }
        }
        throw new RuntimeException('failed assing unique que_id!');
    }

    public function save(MailData $mailData)
    {
        $data = $this->converter->toArray($mailData);
        $data['que_id'] = $this->que_id;
        $data['created_at'] = $this->now->format('Y-m-d H:i:s');
        $data['status'] = MailStatus::READY;
        $this->dba->persist($data);
    }

    public function getQueId(): ?string
    {
        return $this->que_id;
    }
}