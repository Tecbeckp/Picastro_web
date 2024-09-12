<?php

namespace App\Services;

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

class MailerSendService
{
    protected $mailerSend;

    public function __construct()
    {
        $this->mailerSend = new MailerSend(['api_key' => env('MAILERSEND_API_KEY')]);
    }

    public function sendEmail($to, $subject, $htmlContent, $fromEmail, $fromName)
    {
        $recipients = [new Recipient($to, 'Forgot password')];

        $emailParams = (new EmailParams())
            ->setFrom($fromEmail)
            ->setFromName($fromName)
            ->setRecipients($recipients)
            ->setSubject($subject)
            ->setHtml($htmlContent);

        $this->mailerSend->email->send($emailParams);
    }
}