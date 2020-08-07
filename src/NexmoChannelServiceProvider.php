<?php

namespace Illuminate\Notifications;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Nexmo\Client as NexmoClient;
use Nexmo\Message\Client as NexmoMessageClient;

class NexmoChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('nexmo', function ($app) {
                return new Channels\NexmoSmsChannel(
                    $this->app->make(NexmoClient::class),
                    $this->app['config']['services.nexmo.sms_from']
                );
            });

            $service->extend('shortcode', function ($app) {
                $client = tap(new NexmoMessageClient, function ($client) use ($app) {
                    $client->setClient($app->make(NexmoClient::class));
                });

                return new Channels\NexmoShortcodeChannel($client);
            });
        });
    }
}
