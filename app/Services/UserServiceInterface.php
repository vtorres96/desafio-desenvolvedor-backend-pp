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
     * @return void
     */
    public function create(array $data): void;

    /**
     * @param integer $id
     * @return array|null
     */
    public function findById(int $id): ?array;
}
