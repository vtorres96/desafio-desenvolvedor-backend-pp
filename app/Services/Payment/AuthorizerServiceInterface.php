<?php

namespace App\Services\Payment;

use Exception;

/**
 * Interface AuthorizerServiceInterface
 * @package   App\Services\Payment
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface AuthorizerServiceInterface
{
    /**
     * @return boolean
     * @throws Exception
     */
    public function checkTransactionIsAuthorized(): bool;
}
