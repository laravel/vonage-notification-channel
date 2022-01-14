<?php

namespace Illuminate\Tests\Notifications\Channels;

use Illuminate\Notifications\Channels\VonageShortcodeChannel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Tests\Notifications\TestCase;
use Mockery as m;
use Vonage\Message\Client;

class VonageShortcodeChannelTest extends TestCase
{
    public function testShortcodeIsSentViaVonage()
    {
        $notification = new NotificationVonageShortcodeChannelTestNotification;
        $notifiable = new NotificationVonageShortcodeChannelTestNotifiable;

        $channel = new VonageShortcodeChannel(
            $vonage = m::mock(Client::class)
        );

        $vonage->shouldReceive('sendShortcode')
            ->with([
                'type' => 'alert',
                'to' => '5555555555',
                'custom' => [
                    'code' => 'abc123',
                ],
            ])
            ->once();

        $channel->send($notifiable, $notification);
    }
}

class NotificationVonageShortcodeChannelTestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';

    public function routeNotificationForShortcode($notification)
    {
        return $this->phone_number;
    }
}

class NotificationVonageShortcodeChannelTestNotification extends Notification
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
