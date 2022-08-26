<?php

use PHPUnit\Framework\TestCase;
use WScore\MailQueue\Mail\MailData;

class MailDataTest extends TestCase
{
    public function testMailData()
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

        $this->assertEquals('to@example.com', $mail->getTo()[0]->getAddress());
        $this->assertEquals('to-name', $mail->getTo()[0]->getName());

        $this->assertEquals('from@example.com', $mail->getFrom()->getAddress());
        $this->assertEquals('from-name', $mail->getFrom()->getName());

        $this->assertEquals('reply-to@example.com', $mail->getReplyTo()->getAddress());
        $this->assertEquals('reply-name', $mail->getReplyTo()->getName());

        $this->assertEquals('cc@example.com', $mail->getCc()[0]->getAddress());
        $this->assertEquals('bcc@example.com', $mail->getBcc()[0]->getAddress());
        $this->assertEquals('opt1', $mail->getOptions()[0][0]);
        $this->assertEquals('value1', $mail->getOptions()[0][1]);

        $this->assertEquals('subject text', $mail->getSubject());
        $this->assertEquals('body text', $mail->getText());
        $this->assertEquals('body html', $mail->getHtml());
    }

    public function testNewMailDataHasNoMailId()
    {
        $mail = new MailData();
        $mail->addTo('to@example.com', 'to-name');
        $this->assertEquals('', $mail->getMailId());
    }
}