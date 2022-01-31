<?php

namespace Illuminate\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Vonage\SMS\Client as VonageClient;

class VonageShortcodeChannel
{
    /**
     * The Vonage SMS client instance.
     *
     * @var \Vonage\SMS\Client
     */
    protected $client;

    /**
     * Create a new shortcode channel instance.
     *
     * @param  \Vonage\SMS\Client  $client
     * @return void
     */
    public function __construct(VonageClient $client)
    {
        $this->client = $client;
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
