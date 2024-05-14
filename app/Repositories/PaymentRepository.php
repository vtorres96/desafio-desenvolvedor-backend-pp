<?php

namespace App\Repositories;

use App\Models\Payment;

/**
 * Class PaymentRepository
 * @package   App\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    /**
     * @param Payment $model
     */
    public function __construct(
        Payment $model
    ) {
        parent::__construct($model);
    }
}
