<?php

namespace Illuminate\Notifications\Channels;

use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use Vonage\Client as VonageClient;
use Vonage\SMS\Message\SMS;

class VonageSmsChannel
{
    /**
     * The Vonage client instance.
     *
     * @var \Vonage\Client
     */
    protected $client;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Create a new Vonage channel instance.
     *
     * @param  \Vonage\Client  $client
     * @param  string  $from
     * @return void
     */
    public function __construct(VonageClient $client, $from)
    {
        $this->from = $from;
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Vonage\SMS\Collection|null
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

        $vonageSms = new SMS(
            $to,
            $message->from ?: $this->from,
            trim($message->content),
            $message->type
        );

        $vonageSms->setClientRef($message->clientReference);

        if ($message->statusCallback) {
            $vonageSms->setDeliveryReceiptCallback($message->statusCallback);
        }

        return ($message->client ?? $this->client)->sms()->send($vonageSms);
    }
}
