<?php

namespace App\Services;

use App\Repositories\PaymentRepositoryInterface;
use App\Services\Notification\EmailServiceInterface;
use App\Services\Payment\AuthorizerServiceInterface;
use App\Services\UserServiceInterface;

/**
 * Class PaymentServiceFactory
 * @package   App\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentServiceFactory
{
    /**
     * @return \App\Services\PaymentService
     */
    public function __invoke()
    {
        /** @var  \App\Repositories\PaymentRepositoryInterface $paymentRepository */
        $paymentRepository = app(PaymentRepositoryInterface::class);

        /** @var  \App\Services\UserServiceInterface $userService */
        $userService = app(UserServiceInterface::class);

        /** @var  \App\Services\Payment\AuthorizerServiceInterface $authorizerService */
        $authorizerService = app(AuthorizerServiceInterface::class);

        /** @var  \App\Services\Notification\EmailServiceInterface $emailService */
        $emailService = app(EmailServiceInterface::class);

        return new PaymentService(
            $paymentRepository,
            $userService,
            $authorizerService,
            $emailService
        );
    }
}
