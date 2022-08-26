<?php

use WScore\MailQueue\Mail\MailData;

trait CreateMailTrait
{
    private function createMailData(): MailData
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
        return $mail;
    }

    private function compareMails(MailData $mail2, MailData $mail): void
    {
        $this->assertEquals(MailData::class, get_class($mail2));
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