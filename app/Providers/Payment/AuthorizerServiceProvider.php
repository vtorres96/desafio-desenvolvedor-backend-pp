<?php

namespace App\Providers\Payment;

use App\Services\Payment\AuthorizerServiceFactory;
use App\Services\Payment\AuthorizerServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthorizerServiceProvider
 * @package   App\Providers\Payment
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class AuthorizerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AuthorizerServiceInterface::class,
            function () {
                return (new AuthorizerServiceFactory())();
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
