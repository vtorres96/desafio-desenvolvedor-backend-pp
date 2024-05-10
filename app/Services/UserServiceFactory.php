<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;

/**
 * Class UserServiceFactory
 * @package   App\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserServiceFactory
{
    /**
     * @return \App\Services\UserService
     */
    public function __invoke()
    {
        /** @var  \App\Repositories\UserRepositoryInterface $paymentRepository */
        $userRepository = app(UserRepositoryInterface::class);

        return new UserService(
            $userRepository
        );
    }
}
