<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Class UserRepositoryFactory
 * @package   App\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserRepositoryFactory
{
    /**
     * @return \App\Repositories\UserRepository
     */
    public function __invoke()
    {
        /** @var User $model */
        $model = app(User::class);

        return new UserRepository(
            $model
        );
    }
}
