<?php
declare(strict_types=1);

namespace WScore\MailQueue\Queue;

use DateTimeImmutable;
use DateTimeInterface;
use PDO;
use RuntimeException;
use WScore\MailQueue\Mail\MailAddress;
use WScore\MailQueue\Mail\MailData;
use WScore\MailQueue\Mail\MailStatus;

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

    public function __construct(QueueDba $dba, DateTimeInterface $now = null)
    {
        $this->dba = $dba;
        $this->now = $now ?: new DateTimeImmutable('now');
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
        $data = $this->convertToArray($mailData);
        $data['que_id'] = $this->que_id;
        $data['created_at'] = $this->now->format('Y-m-d H:i:s');
        $data['status'] = MailStatus::READY;
        $this->dba->persist($data);
    }

    private function convertToArray(MailData $mailData): array
    {
        return [
            'mail_to' => $this->fromAddressesToString($mailData->getTo()),
            'mail_from' => $mailData->getFrom()->toJSON(),
            'reply_to' => $mailData->getReplyTo()->toJSON(),
            'cc'  => $this->fromAddressesToString($mailData->getCc()),
            'bcc'  => $this->fromAddressesToString($mailData->getBcc()),
            'options' => json_encode($mailData->getOptions()),
            'subject' => $mailData->getSubject(),
            'text' => $mailData->getText(),
            'html' => $mailData->getHtml(),
        ];
    }

    /**
     * @param MailAddress[] $addresses
     * @return string
     */
    private function fromAddressesToString(array $addresses): string
    {
        $list = [];
        foreach ($addresses as $address) {
            $list[] = $address->toArray();
        }
        return json_encode($list);
    }
}