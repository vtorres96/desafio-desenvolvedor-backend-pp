<?php

namespace App\Repositories;

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
        return new UserRepository();
    }
}
