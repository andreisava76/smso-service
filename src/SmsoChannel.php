<?php

namespace NotificationChannels\Smso;

use NotificationChannels\Smso\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class SmsoChannel
{
    public function __construct(Smso $smso)
    {
        $this->smso = $smso;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Smso\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSmso($notifiable);

        if (is_string($message)) {
            $message = SMSOMessage::create($message);
        }

        if (!$message->hasToNumber()) {
            if (!$to = $notifiable->phone_number) {
                $to = $notifiable->routeNotificationFor('sms');
            }

            if (!$to) {
                throw CouldNotSendNotification::phoneNumberNotProvided();
            }

            $message->to($to);
        }

        $params = $message->toArray();

        if ($message instanceof SMSOMessage) {
            $response = $this->smso->sendMessage($params);
        } else {
            return;
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
