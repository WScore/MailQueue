<?php
use PHPUnit\Framework\TestCase;
use WScore\MailQueue\Mail\MailData;
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
}
