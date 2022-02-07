# Upgrade Guide

## Upgrading To 3.0 From 2.x

This major release update is a complete rewrite of the package. We've tried our best to list the most important upgrade points below.

### Minimum Versions

The following required dependency versions have been updated:

- The minimum PHP version is now v8.0
- The minimum Laravel version is now v8.0

### Package Rename

The package has been renamed from `laravel/nexmo-notification-channel` to `laravel/vonage-notification-channel` and you should update your `composer.json` dependency accordingly from:

```json
"require": {
    "laravel/nexmo-notification-channel": "^2.0"
},
```

To:

```json
"require": {
    "laravel/vonage-notification-channel": "^3.0"
},
```

### Vonage Core Client

[The `nexmo/laravel` library](https://github.com/Nexmo/nexmo-laravel) has been merged into this package and its dependency was removed. Instead, this library now directly depends on [the `vonage/client-core` library](https://github.com/Vonage/vonage-php-sdk-core).

If you were depending on functionality from `nexmo/laravel` you can now use it directly from this package. Resolving a new `Vonage\Client` instance can be done form the IoC container:

```php
use Vonage\Client;

$vonageClient = app()->make(Client::class);
```

If you were using the `Nexmo` facade from `nexmo/laravel` you may update this to the `Vonage` one instead:

```php
// Before...
use Nexmo\Laravel\Facade\Nexmo;

Nexmo::message()->send([
    'to' => '14845551244',
    'from' => '16105552344',
    'text' => 'Using the facade to send a message.'
]);

// After...
use Illuminate\Notifications\Facades\Vonage;

Vonage::message()->send([
    'to' => '14845551244',
    'from' => '16105552344',
    'text' => 'Using the facade to send a message.'
]);
```

### HTTP Client Required

A HTTP Client is needed for this package. This can be either implentation of `psr/http-client-implementation`. We recommend requiring Guzzle:

```zsh
composer require guzzlehttp/guzzle:^7.0
```

If you use a different client than Guzzle 7 you can set it through the `VONAGE_HTTP_CLIENT` environment variable:

```
VONAGE_HTTP_CLIENT="GuzzleHttp\Client"
```

### Config Changes

Previously, we recommended placing your SMS from number in the `services.php` file. With the new update, this config option ships with the package. You can set this value with the `VONAGE_SMS_FROM` environment variable instead:

```
VONAGE_SMS_FROM=15556666666
```

And then remove it from your `services.php` config file.

### Rename from Nexmo to Vonage

All Nexmo to Vonage references have been updated. This means all direct class references now need to be updated to the new ones.

The `toNexmo` method needs to be renamed to `toVonage` and the `NexmoMessage` class reference needs to be renamed to `VonageMessage`:

```php
/**
 * Get the Vonage / SMS representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return \Illuminate\Notifications\Messages\VonageMessage
 */
public function toVonage($notifiable)
{
    return (new VonageMessage)
                ->content('Your SMS message content');
}
```

The `routeNotificationForNexmo` method needs to be updated to `routeNotificationForVonage`:

```php
/**
 * Route notifications for the Vonage channel.
 *
 * @param  \Illuminate\Notifications\Notification  $notification
 * @return string
 */
public function routeNotificationForVonage($notification)
{
    return $this->phone_number;
}
```

### Environment Variables Renamed

Additionally to class renames, all environment variables have been renamed as well. Here's a list of the ones you'll need to update:

```
# Before...
NEXMO_KEY=
NEXMO_SECRET=
NEXMO_SIGNATURE_SECRET=
NEXMO_PRIVATE_KEY=
NEXMO_APPLICATION_ID=
NEXMO_APP_NAME=
NEXMO_APP_VERSION=
NEXMO_HTTP_CLIENT=

# After...
VONAGE_KEY=
VONAGE_SECRET=
VONAGE_SIGNATURE_SECRET=
VONAGE_PRIVATE_KEY=
VONAGE_APPLICATION_ID=
VONAGE_APP_NAME=
VONAGE_APP_VERSION=
VONAGE_HTTP_CLIENT=
```

### Shortcode Functionality Removed

Since Vonage is deprecating this feature, all functionality regarding shortcodes has been removed.
