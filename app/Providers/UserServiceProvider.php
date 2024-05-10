<?php

namespace App\Providers;

use App\Repositories\UserRepositoryFactory;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserServiceFactory;
use App\Services\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class UserServiceProvider
 * @package   App\Providers
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserServiceInterface::class,
            function () {
                return (new UserServiceFactory())();
            }
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            function () {
                return (new UserRepositoryFactory())();
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
