<?php

namespace App\Repositories;

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
        return new PaymentRepository();
    }
}
