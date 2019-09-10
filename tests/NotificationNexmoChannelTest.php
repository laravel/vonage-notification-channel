<?php

namespace Illuminate\Tests\Notifications;

use Illuminate\Notifications\Channels\NexmoSmsChannel;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as m;
use Nexmo\Client;
use PHPUnit\Framework\TestCase;

class NotificationNexmoChannelTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testSmsIsSentViaNexmo()
    {
        $notification = new NotificationNexmoChannelTestNotification;
        $notifiable = new NotificationNexmoChannelTestNotifiable;

        $channel = new NexmoSmsChannel(
            $nexmo = m::mock(Client::class), '4444444444'
        );

        $nexmo->shouldReceive('message->send')->with([
            'type' => 'text',
            'from' => '4444444444',
            'to' => '5555555555',
            'text' => 'this is my message',
            'client_ref' => '',
        ]);

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaNexmoWithCustomFrom()
    {
        $notification = new NotificationNexmoChannelTestCustomFromNotification;
        $notifiable = new NotificationNexmoChannelTestNotifiable;

        $channel = new NexmoSmsChannel(
            $nexmo = m::mock(Client::class), '4444444444'
        );

        $nexmo->shouldReceive('message->send')->with([
            'type' => 'unicode',
            'from' => '5554443333',
            'to' => '5555555555',
            'text' => 'this is my message',
            'client_ref' => '',
        ]);

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaNexmoWithCustomFromAndClientRef()
    {
        $notification = new NotificationNexmoChannelTestCustomFromAndClientRefNotification;
        $notifiable = new NotificationNexmoChannelTestNotifiable;

        $channel = new NexmoSmsChannel(
            $nexmo = m::mock(Client::class), '4444444444'
        );

        $nexmo->shouldReceive('message->send')->with([
            'type' => 'unicode',
            'from' => '5554443333',
            'to' => '5555555555',
            'text' => 'this is my message',
            'client_ref' => '11',
        ]);

        $channel->send($notifiable, $notification);
    }
}

class NotificationNexmoChannelTestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';
}

class NotificationNexmoChannelTestNotification extends Notification
{
    public function toNexmo($notifiable)
    {
        return new NexmoMessage('this is my message');
    }
}

class NotificationNexmoChannelTestCustomFromNotification extends Notification
{
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage('this is my message'))->from('5554443333')->unicode();
    }
}

class NotificationNexmoChannelTestCustomFromAndClientRefNotification extends Notification
{
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage('this is my message'))->from('5554443333')->unicode()->clientReference('11');
    }
}
