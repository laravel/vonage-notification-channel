<?php

namespace Illuminate\Notifications;

use Illuminate\Notifications\Channels\VonageShortcodeChannel;
use Illuminate\Notifications\Channels\VonageSmsChannel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use RuntimeException;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\Container;
use Vonage\Client\Credentials\Keypair;
use Vonage\Client\Credentials\SignatureSecret;
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
                return new VonageSmsChannel(
                    $app->make(Client::class),
                    $app['config']['services.vonage.sms_from']
                );
            });

            $service->extend('shortcode', function ($app) {
                $client = tap(new VonageMessageClient, function ($client) use ($app) {
                    $client->setClient($app->make(Client::class));
                });

                return new VonageShortcodeChannel($client);
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
            if (! $appId = $config['application_id'] ?? null) {
                throw new RuntimeException('You must provide vonage.application_id when using a private key');
            }

            $privateKeyCredentials = new Keypair($this->loadPrivateKey($privateKey), $appId);
        }

        $basicCredentials = null;

        if ($apiSecret = $config['api_secret'] ?? null) {
            $basicCredentials = new Basic($config['api_key'], $apiSecret);
        }

        $signatureCredentials = null;

        if ($signatureSecret = $config['signature_secret'] ?? null) {
            $signatureCredentials = new SignatureSecret($config['api_key'], $signatureSecret);
        }

        // We can have basic only, signature only, private key only or we can have
        // private key + basic/signature, so let's work out what's been provided
        if ($basicCredentials && $signatureCredentials) {
            throw new RuntimeException('Provide either vonage.api_secret or vonage.signature_secret, not both.');
        }

        if ($privateKeyCredentials && $basicCredentials) {
            $credentials = new Container($privateKeyCredentials, $basicCredentials);
        } elseif ($privateKeyCredentials && $signatureCredentials) {
            $credentials = new Container($privateKeyCredentials, $signatureCredentials);
        } elseif ($privateKeyCredentials) {
            $credentials = $privateKeyCredentials;
        } elseif ($signatureCredentials) {
            $credentials = $signatureCredentials;
        } elseif ($basicCredentials) {
            $credentials = $basicCredentials;
        } else {
            $combinations = [
                'api_key + api_secret',
                'api_key + signature_secret',
                'private_key + application_id',
                'api_key + api_secret + private_key + application_id',
                'api_key + signature_secret + private_key + application_id',
            ];

            throw new RuntimeException(
                'Please provide Vonage API credentials. Possible combinations: '
                .join(', ', $combinations)
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
