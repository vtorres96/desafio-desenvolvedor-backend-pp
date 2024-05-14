<?php

namespace App\Repositories;

use App\Models\Payment;

/**
 * Class PaymentRepositoryFactory
 * @package   App\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentRepositoryFactory
{
    /**
     * @return \App\Repositories\PaymentRepository
     */
    public function __invoke()
    {
        /** @var Payment $model */
        $model = app(Payment::class);

        return new PaymentRepository(
            $model
        );
    }
}
