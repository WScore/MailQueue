<?php

class MailData
{
    private $to = [];
    private $from;
    private $replyTo;
    private $cc = [];
    private $bcc = [];
    private $subject = '';
    private $text = '';
    private $html = '';

    public function addTo(string $mail, string $name = null): MailData
    {
        $this->to[] = new MailAddress($mail, $name);
        return $this;
    }

    public function setFrom(string $mail, string $name = null): MailData
    {
        $this->from = new MailAddress($mail, $name);
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

    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function setHtml(string $html)
    {
        $this->html = $html;
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
        return $this->from;
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
}