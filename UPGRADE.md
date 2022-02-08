# Upgrade Guide

## Upgrading To 3.0 From 2.x

This major release is a thorough rewrite of this package. Please review the upgrade instruction below to ensure your application is compatible.

### Minimum Versions

The following required dependency versions have been updated:

- The minimum PHP version is now 8.0
- The minimum Laravel version is now 8.0

### Package Name

This package has been renamed from `laravel/nexmo-notification-channel` to `laravel/vonage-notification-channel` to account for Nexmo being acquired by Vonage. Therefore, you should update your `composer.json` dependencies from:

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

If you were depending on functionality from `nexmo/laravel` you may now use it directly from this package. A `Vonage\Client` instance may be resolved from Laravel's service container:

```php
use Vonage\Client;

$vonageClient = app(Client::class);
```

If you were using the `Nexmo` facade from `nexmo/laravel`, you should update your code to use the `Vonage` facade instead:

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

### HTTP Client

An HTTP client such as Guzzle is required for this package. Most Laravel applications already include Guzzle by default:

```zsh
composer require guzzlehttp/guzzle:^7.0
```

### Configuration Changes

Previously, we recommended placing your SMS "from" number in your application's `services.php` file. However, this package includes its own `vonage` configuration file and corresponding environment variables. Therefore, you should now set this value using the `VONAGE_SMS_FROM` environment variable:

```
VONAGE_SMS_FROM=15556666666
```

Once you have defined this environment variable, you may remove the `nexmo` configuration entry from your `services` configuration file.

### Notification Channel Name

The notification channel's name has been updated to `vonage`. Therefore, you should update all `nexmo` channel references in the `via` methods of your notifications to `vonage`:

```php
/**
 * Get the notification's delivery channels.
 *
 * @param  mixed  $notifiable
 * @return array
 */
public function via($notifiable)
{
    return $notifiable->prefers_sms ? ['vonage'] : ['mail', 'database'];
}
```

In addition, any ad-hoc notifications that were previously routing via `nexmo` should have their routing updated to `vonage`:

```php
Notification::route('vonage', '5555555555')->notify(new InvoicePaid($invoice));
```

### Class / Method Renaming

All references to Nexmo have been updated to Vonage, including class names and method names.

For example, all `toNexmo` methods defined on your application's notification classes need to be renamed to `toVonage`. Likewise, the `NexmoMessage` class has been renamed to `VonageMessage`:

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

Within your notifiable models, the `routeNotificationForNexmo` method should to be renamed to `routeNotificationForVonage`:

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

### Environment Variables

Additionally, all environment variables have been renamed to reference Vonage instead of Nexmo:

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

Vonage has deprecated support for "shortcodes" within their SDKs. Therefore, support for this feature has been removed.
