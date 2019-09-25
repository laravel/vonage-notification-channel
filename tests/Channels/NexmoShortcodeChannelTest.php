<?php

namespace Illuminate\Tests\Notifications\Channels;

use Illuminate\Notifications\Channels\NexmoShortcodeChannel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Tests\Notifications\TestCase;
use Mockery as m;
use Nexmo\Message\Client;

class NexmoShortcodeChannelTest extends TestCase
{
    public function testShortcodeIsSentViaNexmo()
    {
        $notification = new NotificationNexmoShortcodeChannelTestNotification;
        $notifiable = new NotificationNexmoShortcodeChannelTestNotifiable;

        $channel = new NexmoShortcodeChannel(
            $nexmo = m::mock(Client::class)
        );

        $nexmo->shouldReceive('sendShortcode')->with([
            'type' => 'alert',
            'to' => '5555555555',
            'custom' => [
                'code' => 'abc123',
            ],
        ]);

        $channel->send($notifiable, $notification);
    }
}

class NotificationNexmoShortcodeChannelTestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';
}

class NotificationNexmoShortcodeChannelTestNotification extends Notification
{
    public function toShortcode($notifiable)
    {
        return [
            'type' => 'alert',
            'custom' => [
                'code' => 'abc123',
            ],
        ];
    }
}
