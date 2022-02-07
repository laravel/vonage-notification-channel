<?php

namespace Illuminate\Notifications;

use Illuminate\Notifications\Channels\VonageSmsChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Vonage\Client;

class VonageChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/vonage.php', 'vonage');

        $this->app->singleton(Client::class, function ($app) {
            $config = $app['config']['vonage'];

            $httpClient = null;

            if ($httpClient = $config['http_client'] ?? null) {
                $httpClient = $app->make($httpClient);
            }

            return Vonage::make($app['config']['vonage'], $httpClient)->client();
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('vonage', function ($app) {
                return new VonageSmsChannel(
                    $app->make(Client::class),
                    $app['config']['vonage.sms_from']
                );
            });
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/vonage.php' => $this->app->configPath('vonage.php'),
            ], 'vonage');
        }
    }
}
