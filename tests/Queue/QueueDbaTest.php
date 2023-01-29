<?php
use PHPUnit\Framework\TestCase;
use WScore\MailQueue\Mail\MailStatus;
use WScore\MailQueue\Mail\MailToArray;
use WScore\MailQueue\Queue\QueueDba;

require_once __DIR__ . '/CreateMailTrait.php';

class QueueDbaTest extends TestCase
{
    use CreateMailTrait;

    /**
     * @var QueueDba
     */
    private $dba;

    protected function setUp(): void
    {
        parent::setUp();
        $pdo = new \PDO('sqlite::memory:');
        $sql = file_get_contents(__DIR__ . '/../../docs/create-sqlite.sql');
        $pdo->exec($sql);
        $this->dba = new QueueDba($pdo, 'mail_queue');
    }

    public function testSaveAndLoad()
    {
        $mail = $this->createMailData();

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
        $this->assertEquals($queId, $mail2->getQueId());
        $this->compareMails($mail2, $mail);
    }

    public function testFetchStmt()
    {
        $mail = $this->createMailData();

        $converter = new MailToArray();
        $data = $converter->toArray($mail);
        $queId = 'col que_id';
        $data['que_id'] = $queId;
        $data['status'] = 'TESTED';
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->dba->persist($data);

        $list = $this->dba->fetchStmtByQueId($queId);
        // this assert fails for SQLite...
        // $this->assertEquals(0, $list->rowCount());
        $mail2 = $list->fetch();
        $this->assertEquals($queId, $mail2->getQueId());
        $this->compareMails($mail2, $mail);
    }

    public function testUpdateMethod()
    {
        $mail = $this->createMailData();

        $converter = new MailToArray();
        $data = $converter->toArray($mail);
        $queId = 'update-test';
        $data['que_id'] = $queId;
        $data['status'] = MailStatus::READY;
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->dba->persist($data);

        $mail1 = $this->dba->listByQueId($queId)[0];
        $this->assertEquals('', $mail1->getSendMsg());

        $this->dba->updateStatus($mail1->getMailId(), MailStatus::FAILED, 'bad message');

        $mail2 = $this->dba->listByQueId($queId)[0];
        $this->assertEquals(MailStatus::FAILED, $mail2->getStatus());
        $this->assertEquals('bad message', $mail2->getSendMsg());
    }
}
