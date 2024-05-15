<?php

namespace App\Services\Notification;

use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;
use SendGrid;

class EmailService
{
    /** @var SendGrid $sendGrid */
    protected SendGrid $sendGrid;

    public function __construct()
    {
        $this->sendGrid = new SendGrid(config('services.sendgrid.api_key'));
    }

    public function sendEmail($to, $subject, $htmlContent)
    {
        $email = new Mail();
        $email->setFrom("victorcdc96@gmail.com", "Victor");
        $email->setSubject($subject);
        $email->addTo($to);
        $email->addContent("text/html", $htmlContent);

        try {
            $response = $this->sendGrid->send($email);
            return $response->statusCode();
        } catch (TypeException $exception) {
            return $exception->getCode();
        }
    }
}
