<?php

use PHPUnit\Framework\TestCase;
use WScore\MailQueue\Mail\MailData;
use WScore\MailQueue\Mail\MailToArray;
use WScore\MailQueue\Queue\QueueDba;

class QueueDbaTest extends TestCase
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var QueueDba
     */
    private $dba;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo = new \PDO('sqlite::memory:');
        $sql = file_get_contents(__DIR__ . '/../../docs/create-sqlite.sql');
        $this->pdo->exec($sql);
        $this->dba = new QueueDba($this->pdo, 'mail_queue');
    }

    public function testSaveAndLoad()
    {
        $mail = new MailData();
        $mail->addTo('to@example.com', 'to-name');
        $mail->setFrom('from@example.com', 'from-name');
        $mail->setReplyTo('reply-to@example.com', 'reply-name');
        $mail->addCc('cc@example.com');
        $mail->addBcc('bcc@example.com');
        $mail->addOption('opt1', 'value1');
        $mail->setSubject('subject text');
        $mail->setText('body text');
        $mail->setHtml('body html');

        $converter = new MailToArray();
        $data = $converter->toArray($mail);
        $queId = 'col que_id';
        $data['que_id'] = $queId;
        $data['status'] = 'TESTED';
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->dba->persist($data);

        $list = $this->dba->listByQueId($queId);
        $this->assertCount(1, $list);
        $mail2 = $list[0];
        $this->assertEquals(MailData::class, get_class($mail2));
        $this->assertEquals($queId, $mail2->getQueId());
        $this->assertEquals($mail->getTo(), $mail2->getTo());
        $this->assertEquals($mail->getFrom(), $mail2->getFrom());
        $this->assertEquals($mail->getReplyTo(), $mail2->getReplyTo());
        $this->assertEquals($mail->getCc(), $mail2->getCc());
        $this->assertEquals($mail->getBcc(), $mail2->getBcc());
        $this->assertEquals($mail->getOptions(), $mail2->getOptions());
        $this->assertEquals($mail->getSubject(), $mail2->getSubject());
        $this->assertEquals($mail->getText(), $mail2->getText());
        $this->assertEquals($mail->getHtml(), $mail2->getHtml());
    }
}
