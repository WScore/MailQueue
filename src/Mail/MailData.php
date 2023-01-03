<?php
declare(strict_types=1);

namespace WScore\MailQueue\Mail;

use stdClass;

use function json_decode;

class MailData
{
    private $mail_id;
    private $que_id;
    private $status;
    private $send_msg;
    private $mail_to = [];
    private $mail_from;
    private $reply_to;
    private $cc = [];
    private $bcc = [];
    private $subject = '';
    private $body_text = '';
    private $body_html = '';
    private $options = [];

    public function __construct()
    {
        if ($this->mail_id) {
            $this->convertMeFromDataBase();
        }
    }

    private function convertMeFromDataBase()
    {
        $this->mail_from = $this->toAddress(json_decode($this->mail_from, true));
        $this->reply_to = $this->toAddress(json_decode($this->reply_to, true));
        $this->mail_to = $this->toAddressList(json_decode((string)$this->mail_to, true));
        $this->cc = $this->toAddressList(json_decode((string)$this->cc, true));
        $this->bcc = $this->toAddressList(json_decode((string)$this->bcc, true));
        $this->options = json_decode((string)$this->options, true);
    }

    private function toAddress(array $data): MailAddress
    {
        return MailAddress::fromArray($data);
    }

    /**
     * @param array $data
     * @return MailAddress[]
     */
    private function toAddressList(array $data): array
    {
        $list = [];
        foreach ($data as $datum) {
            $list[] = MailAddress::fromArray($datum);
        }
        return $list;
    }

    public function addTo(string $mail, string $name = null): MailData
    {
        $this->mail_to[] = new MailAddress($mail, $name);
        return $this;
    }

    public function setFrom(string $mail, string $name = null): MailData
    {
        $this->mail_from = new MailAddress($mail, $name);
        return $this;
    }

    public function setReplyTo(string $mail, string $name = null): MailData
    {
        $this->reply_to = new MailAddress($mail, $name);
        return $this;
    }

    public function addCc(string $mail, string $name = null): MailData
    {
        $this->cc[] = new MailAddress($mail, $name);
        return $this;
    }

    public function addBcc(string $mail, string $name = null): MailData
    {
        $this->bcc[] = new MailAddress($mail, $name);
        return $this;
    }

    public function addOption(string $key, string $value): MailData
    {
        $this->options[] = [$key, $value];
        return $this;
    }

    public function setSubject(string $subject): MailData
    {
        $this->subject = $subject;
        return $this;
    }

    public function setText(string $text): MailData
    {
        $this->body_text = $text;
        return $this;
    }

    public function setHtml(string $html): MailData
    {
        $this->body_html = $html;
        return $this;
    }

    /**
     * @return string|int
     */
    public function getMailId()
    {
        return $this->mail_id;
    }

    /**
     * @return MailAddress[]
     */
    public function getTo(): array
    {
        return $this->mail_to;
    }

    /**
     * @return MailAddress
     */
    public function getFrom(): MailAddress
    {
        return $this->mail_from;
    }

    /**
     * @return MailAddress
     */
    public function getReplyTo(): MailAddress
    {
        return $this->reply_to;
    }

    /**
     * @return MailAddress[]
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @return MailAddress[]
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->body_text;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->body_html;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function getQueId(): ?string
    {
        return $this->que_id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getSendMsg(): ?string
    {
        return $this->send_msg;
    }
}