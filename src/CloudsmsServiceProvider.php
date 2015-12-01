<?php namespace Leelam\Cloudsms;

use Illuminate\Support\ServiceProvider;

class CloudsmsServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */

    public function register()
    {
        /*
        $this->app->bind( 'cloudsms', function () {
            return new Libraries\Gateways\SMS\Cloudsms;
        });*/

        // Fetching the Default connection from cloudsms config file
        $provider = 'cloudsms';  //config('cloudsms.connection');

        //get correct implementation namespace
        if ($provider == 'cloudsms')
        {
            $clientService = Libraries\Gateways\SMS\Cloudsms::class;
        }

        $this->app->bind(
                 Libraries\Contracts\CloudsmsInterface::class, $clientService
        );


        $this->mergeConfigFrom(
            __DIR__ . '/config/cloudsms.php', 'cloudsms'
        );
    }

    public function boot()
    {
        require __DIR__ . '/Http/routes.php';

    }
}