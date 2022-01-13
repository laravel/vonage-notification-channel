<?php

namespace Illuminate\Notifications;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Vonage\Client as VonageClient;
use Vonage\Message\Client as VonageMessageClient;

class VonageChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('vonage', function ($app) {
                return new Channels\VonageSmsChannel(
                    $this->app->make(VonageClient::class),
                    $this->app['config']['services.vonage.sms_from']
                );
            });

            $service->extend('shortcode', function ($app) {
                $client = tap(new VonageMessageClient, function ($client) use ($app) {
                    $client->setClient($app->make(VonageClient::class));
                });

                return new Channels\VonageShortcodeChannel($client);
            });
        });
    }
}
