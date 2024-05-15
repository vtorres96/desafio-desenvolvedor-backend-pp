<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Mail;
use App\Mail\Mail as Mailable;

/**
 * Class EmailService
 * @package   App\Services\Notification
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class EmailService implements EmailServiceInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function sendEmail(array $data): void
    {
        Mail::to($data['email'])->send(new Mailable($data['subject'], $data['content']));
    }
}
