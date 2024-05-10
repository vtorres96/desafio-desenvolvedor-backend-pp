<?php

namespace App\Repositories;

/**
 * Class UserRepository
 * @package   App\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserRepository implements UserRepositoryInterface
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
