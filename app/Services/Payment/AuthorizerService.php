<?php

namespace App\Services\Payment;

use Exception;

/**
 * Class AuthorizerService
 * @package   App\Services\Payment
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class AuthorizerService implements AuthorizerServiceInterface
{
    /**
     * @return boolean
     * @throws Exception
     */
    public function checkTransactionIsAuthorized(): bool
    {
        try {
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
