<?php

namespace App\Providers\Notification;

use App\Services\Notification\EmailServiceFactory;
use App\Services\Notification\EmailServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class EmailServiceProvider
 * @package   App\Providers\Notification
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            EmailServiceInterface::class,
            function () {
                return (new EmailServiceFactory())();
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
