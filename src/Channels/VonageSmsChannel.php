<?php

namespace Illuminate\Notifications\Channels;

use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use Vonage\Client as VonageClient;

class VonageSmsChannel
{
    /**
     * The Vonage client instance.
     *
     * @var \Vonage\Client
     */
    protected $vonage;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Create a new Vonage channel instance.
     *
     * @param  \Vonage\Client  $vonage
     * @param  string  $from
     * @return void
     */
    public function __construct(VonageClient $vonage, $from)
    {
        $this->from = $from;
        $this->vonage = $vonage;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Vonage\Message\Message
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('vonage', $notification)) {
            return;
        }

        $message = $notification->toVonage($notifiable);

        if (is_string($message)) {
            $message = new VonageMessage($message);
        }

        $payload = [
            'type' => $message->type,
            'from' => $message->from ?: $this->from,
            'to' => $to,
            'text' => trim($message->content),
            'client-ref' => $message->clientReference,
        ];

        if ($message->statusCallback) {
            $payload['callback'] = $message->statusCallback;
        }

        return ($message->client ?? $this->vonage)->message()->send($payload);
    }
}
