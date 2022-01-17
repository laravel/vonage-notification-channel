<?php

namespace Illuminate\Notifications\Tests\Unit\Channels;

use Illuminate\Notifications\Channels\VonageSmsChannel;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Vonage\Client;

class VonageSmsChannelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testSmsIsSentViaVonage()
    {
        $notification = new NotificationVonageSmsChannelTestNotification;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldReceive('message->send')
            ->with([
                'type' => 'text',
                'from' => '4444444444',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client-ref' => '',
            ])
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomClient()
    {
        $customVonage = m::mock(Client::class);
        $customVonage->shouldReceive('message->send')
            ->with([
                'type' => 'text',
                'from' => '4444444444',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client-ref' => '',
            ])
            ->once();

        $notification = new NotificationVonageSmsChannelTestCustomClientNotification($customVonage);
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldNotReceive('message->send');

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomFrom()
    {
        $notification = new NotificationVonageSmsChannelTestCustomFromNotification;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldReceive('message->send')
            ->with([
                'type' => 'unicode',
                'from' => '5554443333',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client-ref' => '',
            ])
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomFromAndClient()
    {
        $customVonage = m::mock(Client::class);
        $customVonage->shouldReceive('message->send')
            ->with([
                'type' => 'unicode',
                'from' => '5554443333',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client-ref' => '',
            ])
            ->once();

        $notification = new NotificationVonageSmsChannelTestCustomFromAndClientNotification($customVonage);
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldNotReceive('message->send');

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomFromAndClientRef()
    {
        $notification = new NotificationVonageSmsChannelTestCustomFromAndClientRefNotification;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldReceive('message->send')
            ->with([
                'type' => 'unicode',
                'from' => '5554443333',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client-ref' => '11',
            ])
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomClientFromAndClientRef()
    {
        $customVonage = m::mock(Client::class);
        $customVonage->shouldReceive('message->send')
            ->with([
                'type' => 'unicode',
                'from' => '5554443333',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client-ref' => '11',
            ])
            ->once();

        $notification = new NotificationVonageSmsChannelTestCustomClientFromAndClientRefNotification($customVonage);
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldNotReceive('message->send');

        $channel->send($notifiable, $notification);
    }

    public function testCallbackIsApplied()
    {
        $notification = new NotificationVonageSmsChannelTestCallback;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldReceive('message->send')
            ->with([
                'type' => 'text',
                'from' => '4444444444',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client-ref' => '',
                'callback' => 'https://example.com',
            ])
            ->once();

        $channel->send($notifiable, $notification);
    }
}

class NotificationVonageSmsChannelTestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';

    public function routeNotificationForVonage($notification)
    {
        return $this->phone_number;
    }
}

class NotificationVonageSmsChannelTestNotification extends Notification
{
    public function toVonage($notifiable)
    {
        return new VonageMessage('this is my message');
    }
}

class NotificationVonageSmsChannelTestCustomClientNotification extends Notification
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function toVonage($notifiable)
    {
        return (new VonageMessage('this is my message'))->usingClient($this->client);
    }
}

class NotificationVonageSmsChannelTestCustomFromNotification extends Notification
{
    public function toVonage($notifiable)
    {
        return (new VonageMessage('this is my message'))->from('5554443333')->unicode();
    }
}

class NotificationVonageSmsChannelTestCustomFromAndClientNotification extends Notification
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function toVonage($notifiable)
    {
        return (new VonageMessage('this is my message'))->from('5554443333')->unicode()->usingClient($this->client);
    }
}

class NotificationVonageSmsChannelTestCustomFromAndClientRefNotification extends Notification
{
    public function toVonage($notifiable)
    {
        return (new VonageMessage('this is my message'))->from('5554443333')->unicode()->clientReference('11');
    }
}

class NotificationVonageSmsChannelTestCustomClientFromAndClientRefNotification extends Notification
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function toVonage($notifiable)
    {
        return (new VonageMessage('this is my message'))
            ->from('5554443333')
            ->unicode()
            ->clientReference('11')
            ->usingClient($this->client);
    }
}

class NotificationVonageSmsChannelTestCallback extends Notification
{
    public function toVonage($notifiable)
    {
        return (new VonageMessage('this is my message'))
            ->statusCallback('https://example.com');
    }
}
