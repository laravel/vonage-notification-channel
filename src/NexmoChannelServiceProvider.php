<?php

namespace Illuminate\Notifications;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Nexmo\Client as NexmoClient;

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
        });
    }
}
