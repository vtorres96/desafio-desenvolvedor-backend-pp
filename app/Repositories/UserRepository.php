<?php

namespace App\Repositories;

use App\Models\User;
use Exception;

/**
 * Class UserRepository
 * @package   App\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @param User $model
     */
    public function __construct(
        User $model
    ) {
        parent::__construct($model);
    }

    /**
     * @param string $cpfCnpj
     * @param string $email
     * @return array
     * @throws Exception
     */
    public function getByCpfCnpjOrEmail(string $cpfCnpj, string $email): array
    {
        return $this->model->newQuery()
            ->where('cpf_cnpj', $cpfCnpj)
            ->orWhere('email', $email)
            ->get()
            ->toArray();
    }
}
