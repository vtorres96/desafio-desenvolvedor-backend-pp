<?php

namespace App\Services\Notification;

/**
 * Class EmailServiceFactory
 * @package   App\Services\Notification
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class EmailServiceFactory
{
    /**
     * @return \App\Services\Notification\EmailService
     */
    public function __invoke(): EmailService
    {
        return new EmailService();
    }
}
