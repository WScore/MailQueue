<?php

class MailData
{
    private $mail_id;
    private $que_id;
    private $status;
    private $to = [];
    private $mail_from;
    private $replyTo;
    private $cc = [];
    private $bcc = [];
    private $subject = '';
    private $text = '';
    private $html = '';
    private $options = [];

    public function addTo(string $mail, string $name = null): MailData
    {
        $this->to[] = new MailAddress($mail, $name);
        return $this;
    }

    public function setFrom(string $mail, string $name = null): MailData
    {
        $this->mail_from = new MailAddress($mail, $name);
        return $this;
    }

    public function setReplyTo(string $mail, string $name = null): MailData
    {
        $this->replyTo = new MailAddress($mail, $name);
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
        $this->text = $text;
        return $this;
    }

    public function setHtml(string $html): MailData
    {
        $this->html = $html;
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
        return $this->to;
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
        return $this->replyTo;
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
        return $this->text;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}