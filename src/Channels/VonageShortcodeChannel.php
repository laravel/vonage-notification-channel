<?php

namespace Illuminate\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Vonage\Message\Client as VonageClient;

class VonageShortcodeChannel
{
    /**
     * The Vonage message client instance.
     *
     * @var \Vonage\Message\Client
     */
    protected $vonage;

    /**
     * Create a new channel instance.
     *
     * @param  \Vonage\Message\Client  $Vonage
     * @return void
     */
    public function __construct(VonageClient $Vonage)
    {
        $this->vonage = $Vonage;
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

        $this->vonage->sendShortcode($shortcode);
    }
}
