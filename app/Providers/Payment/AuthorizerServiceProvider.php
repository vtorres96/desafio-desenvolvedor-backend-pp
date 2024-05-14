<?php

namespace App\Providers;

use App\Repositories\PaymentRepositoryFactory;
use App\Repositories\PaymentRepositoryInterface;
use App\Services\PaymentServiceFactory;
use App\Services\PaymentServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class PaymentServiceProvider
 * @package   App\Providers
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            PaymentServiceInterface::class,
            function () {
                return (new PaymentServiceFactory())();
            }
        );

        $this->app->bind(
            PaymentRepositoryInterface::class,
            function () {
                return (new PaymentRepositoryFactory())();
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
