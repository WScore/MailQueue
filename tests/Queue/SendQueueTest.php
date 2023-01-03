<?php

use PHPUnit\Framework\TestCase;
use WScore\MailQueue\Queue\QueueDba;
use WScore\MailQueue\Queue\SaveQueue;
use WScore\MailQueue\Queue\SendQueue;

require_once __DIR__ . '/CreateMailTrait.php';
require_once __DIR__ . '/Senders/EmptySender.php';

class SendQueueTest extends TestCase
{
    use CreateMailTrait;

    /**
     * @var SaveQueue
     */
    private $save;
    /**
     * @var QueueDba
     */
    private $dba;
    /**
     * @var SendQueue
     */
    private $queue;
    /**
     * @var EmptySender
     */
    private $sender;

    protected function setUp(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $sql = file_get_contents(__DIR__ . '/../../docs/create-sqlite.sql');
        $pdo->exec($sql);
        $this->save = SaveQueue::forgeWithQueId($pdo);
        $this->dba = new QueueDba($pdo, 'mail_queue');
        $this->queue = new SendQueue($this->dba);
        $this->sender = new EmptySender();
        $this->queue->setSender($this->sender);
    }

    public function testQue()
    {
        $queId = 'test que';
        $this->save->withQueId($queId);
        $mail = $this->createMailData();
        $mail->setSubject('first mail');
        $this->save->save($mail);
        $mail->setSubject('second mail');
        $this->save->save($mail);

        $this->queue->sendQueId($queId);
        $sendMails = $this->sender->getMails();

        $this->assertEquals(2, count($sendMails));
        $this->assertEquals('first mail', $sendMails[0]->getSubject());
        $this->assertEquals('second mail', $sendMails[1]->getSubject());
    }

    public function testSendErrorException()
    {
        $queId = 'test que';
        $this->save->withQueId($queId);
        $mail = $this->createMailData();
        $mail->setSubject('first mail');
        $this->save->save($mail);
        $mail->setSubject('throw e');
        $this->save->save($mail);

        $this->queue->sendQueId($queId);
        $sendMails = $this->sender->getMails();

        $list = $this->dba->listByQueId($queId);
        $this->assertEquals(1, count($list));
        $mailFailed = $list[0];
        $this->assertEquals('throw e', $mailFailed->getSubject());
        $this->assertEquals('FAILED', $mailFailed->getStatus());
        $this->assertEquals('thrown an exception', $mailFailed->getSendMsg());
    }
}
