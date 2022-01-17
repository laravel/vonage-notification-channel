<?php

namespace Illuminate\Notifications\Tests\Unit\Channels;

use Illuminate\Notifications\Channels\VonageShortcodeChannel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Vonage\Message\Client;

class VonageShortcodeChannelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

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
