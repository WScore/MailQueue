<?php

interface SenderInterface
{
    public function send(MailData $mailData): bool;
}