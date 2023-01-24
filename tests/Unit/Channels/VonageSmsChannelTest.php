<?php

namespace Illuminate\Notifications\Tests\Unit\Channels;

use Hamcrest\Core\IsEqual;
use Illuminate\Notifications\Channels\VonageSmsChannel;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Vonage\Client;
use Vonage\SMS\Message\SMS;

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

        $mockSms = (new SMS(
            '5555555555',
            '4444444444',
            'this is my message',
            'text'
        ));

        $vonage->shouldReceive('sms->send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomClient()
    {
        $customVonage = m::mock(Client::class);
        $customVonage->shouldReceive('sms->send')
            ->with(IsEqual::equalTo(new SMS(
                '5555555555',
                '4444444444',
                'this is my message',
                'text'
            )))
            ->once();

        $notification = new NotificationVonageSmsChannelTestCustomClientNotification($customVonage);
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldNotReceive('sms->send');

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomFrom()
    {
        $notification = new NotificationVonageSmsChannelTestCustomFromNotification;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $mockSms = (new SMS(
            '5555555555',
            '5554443333',
            'this is my message'
        ));

        $vonage->shouldReceive('sms->send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomFromAndClient()
    {
        $customVonage = m::mock(Client::class);

        $mockSms = new SMS(
            '5555555555',
            '5554443333',
            'this is my message',
            'unicode'
        );

        $customVonage->shouldReceive('sms->send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $notification = new NotificationVonageSmsChannelTestCustomFromAndClientNotification($customVonage);
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldNotReceive('sms->send');

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomFromAndClientRef()
    {
        $notification = new NotificationVonageSmsChannelTestCustomFromAndClientRefNotification;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $mockSms = new SMS(
            '5555555555',
            '5554443333',
            'this is my message',
            'unicode'
        );

        $mockSms->setClientRef('11');

        $vonage->shouldReceive('sms->send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomClientFromAndClientRef()
    {
        $customVonage = m::mock(Client::class);

        $mockSms = new SMS(
            '5555555555',
            '5554443333',
            'this is my message',
            'unicode'
        );

        $mockSms->setClientRef('11');

        $customVonage->shouldReceive('sms->send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $notification = new NotificationVonageSmsChannelTestCustomClientFromAndClientRefNotification($customVonage);
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldNotReceive('sms->send');

        $channel->send($notifiable, $notification);
    }

    public function testCallbackIsApplied()
    {
        $notification = new NotificationVonageSmsChannelTestCallback;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new VonageSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $mockSms = (new SMS(
            '5555555555',
            '4444444444',
            'this is my message',
            'text'
        ));

        $mockSms->setDeliveryReceiptCallback('https://example.com');

        $vonage->shouldReceive('sms->send')
               ->with(IsEqual::equalTo($mockSms))
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
        return (new VonageMessage('this is my message'))->statusCallback('https://example.com');
    }
}
