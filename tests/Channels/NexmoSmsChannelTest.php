<?php

namespace Illuminate\Tests\Notifications\Channels;

use Illuminate\Notifications\Channels\NexmoSmsChannel;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Tests\Notifications\TestCase;
use Mockery as m;
use Nexmo\Client;

class NexmoSmsChannelTest extends TestCase
{
    public function testSmsIsSentViaNexmo()
    {
        $notification = new NotificationNexmoSmsChannelTestNotification;
        $notifiable = new NotificationNexmoSmsChannelTestNotifiable;

        $channel = new NexmoSmsChannel(
            $nexmo = m::mock(Client::class), '4444444444'
        );

        $nexmo->shouldReceive('message->send')
            ->with([
                'type' => 'text',
                'from' => '4444444444',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client_ref' => '',
            ])
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaNexmoWithCustomFrom()
    {
        $notification = new NotificationNexmoSmsChannelTestCustomFromNotification;
        $notifiable = new NotificationNexmoSmsChannelTestNotifiable;

        $channel = new NexmoSmsChannel(
            $nexmo = m::mock(Client::class), '4444444444'
        );

        $nexmo->shouldReceive('message->send')
            ->with([
                'type' => 'unicode',
                'from' => '5554443333',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client_ref' => '',
            ])
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaNexmoWithCustomFromAndClientRef()
    {
        $notification = new NotificationNexmoSmsChannelTestCustomFromAndClientRefNotification;
        $notifiable = new NotificationNexmoSmsChannelTestNotifiable;

        $channel = new NexmoSmsChannel(
            $nexmo = m::mock(Client::class), '4444444444'
        );

        $nexmo->shouldReceive('message->send')
            ->with([
                'type' => 'unicode',
                'from' => '5554443333',
                'to' => '5555555555',
                'text' => 'this is my message',
                'client_ref' => '11',
            ])
            ->once();

        $channel->send($notifiable, $notification);
    }
}

class NotificationNexmoSmsChannelTestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';

    public function routeNotificationForNexmo($notification)
    {
        return $this->phone_number;
    }
}

class NotificationNexmoSmsChannelTestNotification extends Notification
{
    public function toNexmo($notifiable)
    {
        return new NexmoMessage('this is my message');
    }
}

class NotificationNexmoSmsChannelTestCustomFromNotification extends Notification
{
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage('this is my message'))->from('5554443333')->unicode();
    }
}

class NotificationNexmoSmsChannelTestCustomFromAndClientRefNotification extends Notification
{
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage('this is my message'))->from('5554443333')->unicode()->clientReference('11');
    }
}
