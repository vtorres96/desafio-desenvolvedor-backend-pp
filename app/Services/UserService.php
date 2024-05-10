<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class UserService
 * @package   App\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserService implements UserServiceInterface
{
    /** @var \App\Repositories\UserRepositoryInterface */
    private UserRepositoryInterface $userRepository;

    /**
     * UserService constructor.
     * @param \App\Repositories\UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->userRepository->create($data);
    }
}
