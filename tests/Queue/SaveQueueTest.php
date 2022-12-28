<?php

use PHPUnit\Framework\TestCase;
use WScore\MailQueue\Mail\MailData;
use WScore\MailQueue\Mail\MailStatus;
use WScore\MailQueue\Queue\QueueDba;
use WScore\MailQueue\Queue\SaveQueue;

require_once __DIR__ . '/CreateMailTrait.php';

class SaveQueueTest extends TestCase
{
    use CreateMailTrait;

    /**
     * @var SaveQueue
     */
    private $queue;
    /**
     * @var QueueDba
     */
    private $dba;

    protected function setUp(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $sql = file_get_contents(__DIR__ . '/../../docs/create-sqlite.sql');
        $pdo->exec($sql);
        $this->dba = new QueueDba($pdo, 'mail_queue');
        $this->queue = SaveQueue::forgeWithQueId($pdo);
    }

    public function testSave()
    {
        $mail = $this->createMailData();
        $this->queue->createQueId();
        $queId = $this->queue->getQueId();
        $this->queue->save($mail);
        $list = $this->dba->listByQueId($queId);
        $this->assertCount(1, $list);
        $mail2 = $list[0];
        $this->compareMails($mail2, $mail);
    }

    public function testQueIdAreUnique()
    {
        $mail = $this->createMailData();
        $this->queue->createQueId();
        $this->queue->save($mail);
        $que1 = $this->queue->getQueId();

        $this->assertFalse($this->dba->isQueIdUnique($que1));

        $this->queue->createQueId();
        $que2 = $this->queue->getQueId();
        $this->assertNotEquals($que1, $que2);
    }

    public function testWithQueId()
    {
        $queId = 'test-que-id';
        $this->queue->withQueId($queId);
        $this->assertEquals($queId, $this->queue->getQueId());
    }

    public function testSuccess()
    {
        // create a new mail.
        $mail = $this->createMailData();
        $this->queue->save($mail);

        // save and retrieve.
        $queId = $this->queue->getQueId();
        $mail = $this->getMailFromQueId($queId);

        $this->assertTrue((bool) $mail->getMailId()); // check saved!

        $this->dba->failed($mail);
        $mailOK = $this->getMailFromQueId($queId);
        $this->assertEquals(MailStatus::FAILED, $mailOK->getStatus());

        $this->dba->success($mail);
        $mailOK = $this->getMailFromQueId($queId);
        $this->assertNull($mailOK);
    }

    /**
     * @param string|null $queId
     * @return ?MailData
     */
    private function getMailFromQueId(?string $queId): ?MailData
    {
        $list = $this->dba->listByQueId($queId);
        return $list[0] ?? null;
    }
}
