<?php

namespace Illuminate\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Nexmo\Message\Client as NexmoClient;

class NexmoShortcodeChannel
{
    /**
     * The Nexmo message client instance.
     *
     * @var \Nexmo\Message\Client
     */
    protected $nexmo;

    /**
     * Create a new channel instance.
     *
     * @param  \Nexmo\Message\Client  $nexmo
     * @return void
     */
    public function __construct(NexmoClient $nexmo)
    {
        $this->nexmo = $nexmo;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('shortcode', $notification)) {
            return;
        }

        $shortcode = array_merge(['to' => $to], $notification->toShortcode($notifiable));

        $this->nexmo->sendShortcode($shortcode);
    }
}
