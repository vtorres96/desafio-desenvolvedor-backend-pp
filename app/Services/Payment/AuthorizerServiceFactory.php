<?php

namespace App\Services\Payment;

/**
 * Class AuthorizerServiceFactory
 * @package   App\Services\Payment
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class AuthorizerServiceFactory
{
    /**
     * @return \App\Services\Payment\AuthorizerService
     */
    public function __invoke()
    {
        return new AuthorizerService();
    }
}
