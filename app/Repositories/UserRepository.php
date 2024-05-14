<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Class UserRepository
 * @package   App\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserRepository implements UserRepositoryInterface
{
    /** @var User $model */
    private User $model;

    /**
     * @param User $model
     */
    public function __construct(
        User $model
    )
    {
        $this->model = $model;
    }
    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $lastId = $this->model->newQuery()->insertGetId($data);
        return $this->model->newQuery()->find($lastId)->toArray();
    }
}
