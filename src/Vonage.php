<?php

namespace Illuminate\Notifications;

use Illuminate\Support\Str;
use Psr\Http\Client\ClientInterface;
use RuntimeException;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Credentials\Container;
use Vonage\Client\Credentials\Keypair;
use Vonage\Client\Credentials\SignatureSecret;

class Vonage
{
    /**
     * The Vonage configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The HttpClient instance, if provided.
     *
     * @var \Psr\Http\Client\ClientInterface
     */
    protected $client;

    /**
     * Create a new Vonage instance.
     *
     * @param  array  $config
     * @param  \Psr\Http\Client\ClientInterface|null  $client
     * @return void
     */
    public function __construct(array $config = [], ?ClientInterface $client = null)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * Create a new Vonage instance.
     *
     * @param  array  $config
     * @param  \Psr\Http\Client\ClientInterface|null  $client
     * @return static
     */
    public static function make(array $config, ?ClientInterface $client = null)
    {
        return new static($config, $client);
    }

    /**
     * Create a new Vonage Client.
     *
     * @return \Vonage\Client
     *
     * @throws \RuntimeException
     */
    public function client()
    {
        $privateKeyCredentials = null;

        if ($privateKey = $this->config['private_key'] ?? null) {
            if (! $appId = $this->config['application_id'] ?? null) {
                throw new RuntimeException('You must provide a vonage.application_id when using a private key.');
            }

            $privateKeyCredentials = new Keypair($this->loadPrivateKey($privateKey), $appId);
        }

        $basicCredentials = null;

        if ($apiSecret = $this->config['api_secret'] ?? null) {
            $basicCredentials = new Basic($this->config['api_key'], $apiSecret);
        }

        $signatureCredentials = null;

        if ($signatureSecret = $this->config['signature_secret'] ?? null) {
            $signatureCredentials = new SignatureSecret($this->config['api_key'], $signatureSecret, $this->config['signature_method'] ?? 'md5hash');
        }

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
                'Please provide your Vonage API credentials. Possible combinations: '
                .join(', ', $combinations)
            );
        }

        return new Client($credentials, $this->config, $this->client);
    }

    /**
     * Load the private key contents from the root directory of the application.
     *
     * @return string
     */
    protected function loadPrivateKey($key)
    {
        if (Str::startsWith($key, '-----BEGIN PRIVATE KEY-----')) {
            return $key;
        }

        if (! Str::startsWith($key, '/')) {
            $key = base_path($key);
        }

        return trim(file_get_contents($key));
    }
}
