<?php

namespace App\Repositories;

/**
 * Class PaymentRepository
 * @package   App\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $data;
    }
}
