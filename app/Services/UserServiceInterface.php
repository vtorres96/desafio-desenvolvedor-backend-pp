<?php

namespace App\Services;

/**
 * Interface UserServiceInterface
 * @package   App\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface UserServiceInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array;
}
