<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Exception;
use Throwable;

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
     * @return void
     * @throws Exception
     */
    public function create(array $data): void
    {
        try {
            $data = $this->applyHashPassword($data);
            $this->userRepository->beginTransaction();
            $this->userRepository->create($data);
            $this->userRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->userRepository->rollbackTransaction();
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param integer $id
     * @return array|null
     * @throws Exception
     */
    public function findById(int $id): ?array
    {
        try {
            $response = $this->userRepository->findById($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $response;
    }

    private function applyHashPassword(array $data): array
    {
        return array_merge(
            $data,
            ['password' => bcrypt($data['password'])]
        );
    }
}
