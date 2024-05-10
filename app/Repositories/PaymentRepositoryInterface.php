<?php

namespace App\Repositories;

/**
 * Interface PaymentRepositoryInterface
 * @package   App\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface PaymentRepositoryInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array;
}
