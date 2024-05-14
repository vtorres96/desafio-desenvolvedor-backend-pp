<?php

namespace App\Services;

/**
 * Interface PaymentServiceInterface
 * @package   App\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface PaymentServiceInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function transfer(array $data): array;
}
