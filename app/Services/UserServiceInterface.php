<?php

namespace App\Services;

use Exception;

/**
 * Interface UserServiceInterface
 * @package   App\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface UserServiceInterface
{
    /** @var string */
    public const COMMON = 'common';

    /** @var string */
    public const SHOPKEEPER = 'shopkeeper';

    /** @var array */
    public const TYPE_USERS = [
        self::COMMON,
        self::SHOPKEEPER
    ];

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array;

    /**
     * @param integer $id
     * @return array|null
     */
    public function findById(int $id): ?array;

    /**
     * @param int $id
     * @param array $data
     */
    public function update(int $id, array $data): void;

    /**
     * @param string $cpfCnpj
     * @param string $email
     * @return array
     * @throws Exception
     */
    public function getByCpfCnpjOrEmail(string $cpfCnpj, string $email): array;
}
