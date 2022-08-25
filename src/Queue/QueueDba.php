<?php
/** @noinspection SqlNoDataSourceInspection */

declare(strict_types=1);

namespace WScore\MailQueue\Queue;

use PDO;
use WScore\MailQueue\Mail\MailData;
use WScore\MailQueue\Mail\MailStatus;


class QueueDba
{
    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var string
     */
    private $table;

    public function __construct(PDO $pdo, string $table = 'mail_queue')
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    public function isQueIdUnique(string $que_id): bool
    {
        $sql = "SELECT que_id FROM {$this->table} WHERE que_id = :que_id LIMIT 1";
        $stm = $this->pdo->prepare($sql);
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $stm->execute([
                          'que_id' => $que_id,
                      ]);
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            return false;
        }
        return true;
    }

    public function persist(array $data): bool
    {
        $colList = [];
        $valList = [];
        foreach (array_keys($data) as $column) {
            $colList[] = $column;
            $valList[] = ":" . $column;
        }
        $columns = implode(', ', $colList);
        $values = implode(', ', $valList);
        $sql = "INSERT INTO {$this->table} ({$columns} VALUES ({$values}));";
        return (bool)$this->pdo->exec($sql);
    }

    /**
     * @param string $que_id
     * @return MailData[]
     */
    public function listByQueId(string $que_id): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE que_id = :que_id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([
                          'que_id' => $que_id,
                      ]);
        return $stm->fetchAll(PDO::FETCH_CLASS, MailData::class);
    }

    public function success(MailData $mailData)
    {
        $mail_id = $mailData->getMailId();
        $this->updateStatus($mail_id, MailStatus::SENT);
    }

    public function failed(MailData $mailData)
    {
        $mail_id = $mailData->getMailId();
        $this->updateStatus($mail_id, MailStatus::FAILED);
    }

    private function updateStatus($mail_id, string $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status WHERE mail_id = :mail_id";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([
                          'status' => $status,
                          'mail_id' => $mail_id,
                      ]);
    }
}