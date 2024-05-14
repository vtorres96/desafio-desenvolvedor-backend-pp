<?php

namespace App\Repositories;

use Exception;

/**
 * Interface UserRepositoryInterface
 * @package   App\Repositories
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface UserRepositoryInterface
{
    /**
     * @param string $cpfCnpj
     * @param string $email
     * @return array
     * @throws Exception
     */
    public function getByCpfCnpjOrEmail(string $cpfCnpj, string $email): array;
}
