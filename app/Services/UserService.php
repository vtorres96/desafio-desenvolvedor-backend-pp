<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Exception;

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
     * @throws Exception
     */
    public function create(array $data): array
    {
        try {
            $this->checkCpfAndEmailHasDuplicated($data);

            $data = $this->applyHashPassword($data);

            $this->userRepository->beginTransaction();
            $response = $this->userRepository->create($data)->toArray();
            $this->userRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->userRepository->rollbackTransaction();
            throw new Exception($exception->getMessage());
        }

        return $response;
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

    /**
     * @param integer $id
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function update(int $id, array $data): void
    {
        try {
            $this->checkCpfAndEmailHasDuplicated($data);

            $data = $this->applyHashPassword($data);

            $this->userRepository->beginTransaction();
            $this->userRepository->update($id, $data);
            $this->userRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->userRepository->rollbackTransaction();
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param string $cpfCnpj
     * @param string $email
     * @return array
     * @throws Exception
     */
    public function getByCpfCnpjOrEmail(string $cpfCnpj, string $email): array
    {
        try {
            $response = $this->userRepository->getByCpfCnpjOrEmail($cpfCnpj, $email);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $response;
    }

    /**
     * @param array $data
     * @return array
     */
    private function applyHashPassword(array $data): array
    {
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $data;
    }

    /**
     * @param string $cpfCnpj
     * @param string $email
     * @throws Exception
     */
    private function validateDuplicateCpfAndEmail(string $cpfCnpj, string $email): void
    {
        $response = $this->getByCpfCnpjOrEmail($cpfCnpj, $email);

        if (!empty($response)) {
            throw new Exception('Já existe um usuário com o CPF/CNPJ ou e-mail fornecido.');
        }
    }

    /**
     * @param array $data
     * @throws Exception
     */
    private function checkCpfAndEmailHasDuplicated(array $data): void
    {
        if (isset($data['cpf_cnpj']) && isset($data['email'])) {
            $this->validateDuplicateCpfAndEmail($data['cpf_cnpj'], $data['email']);
        }
    }
}
