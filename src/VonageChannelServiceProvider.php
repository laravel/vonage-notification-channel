<?php

namespace Illuminate\Notifications;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use RuntimeException;
use Vonage\Client;
use Vonage\Message\Client as VonageMessageClient;

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
            return $this->createVonageClient($app['config']['vonage']);
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('vonage', function ($app) {
                return new Channels\VonageSmsChannel(
                    $this->app->make(Client::class),
                    $this->app['config']['services.vonage.sms_from']
                );
            });

            $service->extend('shortcode', function ($app) {
                $client = tap(new VonageMessageClient, function ($client) use ($app) {
                    $client->setClient($app->make(Client::class));
                });

                return new Channels\VonageShortcodeChannel($client);
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

    /**
     * Create a new Vonage Client.
     *
     * @param  array  $config
     * @return \Vonage\Client
     *
     * @throws \RuntimeException
     */
    protected function createVonageClient(array $config)
    {
        $privateKeyCredentials = null;

        if ($privateKey = $config['private_key'] ?? null) {
            if ($appId = $config['application_id'] ?? null) {
                throw new RuntimeException('You must provide vonage.application_id when using a private key');
            }

            $privateKeyCredentials = new Client\Credentials\Keypair($this->loadPrivateKey($privateKey), $appId);
        }

        $basicCredentials = null;

        if ($apiSecret = $config['api_secret'] ?? null) {
            $basicCredentials = new Client\Credentials\Basic($config['api_key'], $apiSecret);
        }

        $signatureCredentials = null;

        if ($signatureSecret = $config['signature_secret'] ?? null) {
            $signatureCredentials = new Client\Credentials\SignatureSecret($config['api_key'], $signatureSecret);
        }

        // We can have basic only, signature only, private key only or we can have
        // private key + basic/signature, so let's work out what's been provided
        if ($basicCredentials && $signatureCredentials) {
            throw new RuntimeException('Provide either nexmo.api_secret or nexmo.signature_secret');
        }

        if ($privateKeyCredentials && $basicCredentials) {
            $credentials = new Client\Credentials\Container(
                $privateKeyCredentials,
                $basicCredentials
            );
        } else if ($privateKeyCredentials && $signatureCredentials) {
            $credentials = new Client\Credentials\Container(
                $privateKeyCredentials,
                $signatureCredentials
            );
        } else if ($privateKeyCredentials) {
            $credentials = $privateKeyCredentials;
        } else if ($signatureCredentials) {
            $credentials = $signatureCredentials;
        } else if ($basicCredentials) {
            $credentials = $basicCredentials;
        } else {
            $possibleNexmoKeys = [
                'api_key + api_secret',
                'api_key + signature_secret',
                'private_key + application_id',
                'api_key + api_secret + private_key + application_id',
                'api_key + signature_secret + private_key + application_id',
            ];

            throw new RuntimeException(
                'Please provide Nexmo API credentials. Possible combinations: '
                . join(", ", $possibleNexmoKeys)
            );
        }

        $httpClient = null;

        if ($httpClient = $config['http_client']) {
            $httpClient = $this->app->make($httpClient);
        }

        $options = Arr::only($config, [
            'private_key',
            'application_id',
            'api_key',
            'api_secret',
            'shared_secret',
            'app',
        ]);

        return new Client($credentials, $options, $httpClient);
    }

    /**
     * Load private key contents from root directory.
     *
     * @return string
     */
    protected function loadPrivateKey($key)
    {
        if ($this->app->runningUnitTests()) {
            return '===FAKE-KEY===';
        }

        if (Str::startsWith($key, '-----BEGIN PRIVATE KEY-----')) {
            return $key;
        }

        // If it's a relative path, start searching in the project root
        if ($key[0] !== '/') {
            $key = $this->app->basePath($key);
        }

        return file_get_contents($key);
    }
}
