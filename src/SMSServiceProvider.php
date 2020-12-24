<?php

namespace SimpleSoftwareIO\SMS;

use Illuminate\Support\ServiceProvider;

class SMSServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/sms.php' => config_path('sms.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('sms', function ($container) {
            $this->registerSender();
            $sms = new SMS($container['sms.sender']);
            $this->setSMSDependencies($sms, $container);

            //Set the from setting
            if ($container['config']->has('sms.from')) {
                $sms->alwaysFrom($container['config']['sms']['from']);
            }

            return $sms;
        });
    }

    /**
     * Register the correct driver based on the config file.
     */
    public function registerSender()
    {
        $this->app->singleton('sms.sender', function ($container) {
            return (new DriverManager($container))->driver();
        });
    }

    /**
     * Set a few dependencies on the sms instance.
     *
     * @param SMS $sms
     * @param  $container
     */
    private function setSMSDependencies($sms, $container)
    {
        $sms->setContainer($container);
        $sms->setQueue($container['queue']);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sms', 'sms.sender'];
    }
}
