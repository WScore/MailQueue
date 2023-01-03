<?php

namespace WScore\MailQueue\Mail;

class MailToArray
{
    public function toArray(MailData $mailData): array
    {
        return [
            'mail_to' => $this->fromAddressesToString($mailData->getTo()),
            'mail_from' => $mailData->getFrom()->toJSON(),
            'reply_to' => $mailData->getReplyTo()->toJSON(),
            'cc'  => $this->fromAddressesToString($mailData->getCc()),
            'bcc'  => $this->fromAddressesToString($mailData->getBcc()),
            'options' => json_encode($mailData->getOptions()),
            'subject' => $mailData->getSubject(),
            'body_text' => $mailData->getText(),
            'body_html' => $mailData->getHtml(),
        ];
    }

    /**
     * @param MailAddress[] $addresses
     * @return string
     */
    private function fromAddressesToString(array $addresses): string
    {
        $list = [];
        foreach ($addresses as $address) {
            $list[] = $address->toArray();
        }
        return json_encode($list);
    }
}