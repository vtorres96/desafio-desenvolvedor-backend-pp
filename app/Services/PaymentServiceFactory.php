<?php

namespace App\Services;

use App\Repositories\PaymentRepositoryInterface;

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

        return new PaymentService(
            $paymentRepository
        );
    }
}
