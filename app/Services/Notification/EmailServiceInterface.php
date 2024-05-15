<?php

namespace App\Services\Notification;

/**
 * Interface EmailServiceInterface
 * @package   App\Services\Notification
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface EmailServiceInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function sendEmail(array $data): void;
}
