<?php

namespace NotificationChannel;

class SmsoMessage
{
    protected $payload = [];

    /**
     * @param  string  $message
     */
    public function __construct(string $message = '')
    {
        $this->payload['text'] = $message;
        $this->payload['json'] = 1;
    }

    /**
     * Get the payload value for a given key.
     *
     * @param  string  $key
     * @return mixed|null
     */
    public function getPayloadValue(string $key)
    {
        return $this->payload[$key] ?? null;
    }

    /**
     * Return new SMSOMessage object.
     *
     * @param string $message
     * @return SmsoMessage
     */
    public static function create(string $message = ''): self
    {
        return new self($message);
    }

    /**
     * Returns if recipient number is given or not.
     *
     * @return bool
     */
    public function hasToNumber(): bool
    {
        return isset($this->payload['to']);
    }

    /**
     * Return payload.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * Set message content.
     *
     * @param string $message
     * @return SmsoMessage
     */
    public function content(string $message): self
    {
        $this->payload['body'] = $message;

        return $this;
    }

    /**
     * Set recipient phone number.
     *
     * @param string $to
     * @return SmsoMessage
     */
    public function to(string $to): self
    {
        $this->payload['to'] = $to;

        return $this;
    }

    /**
     * Set sender name.
     *
     * @param string $from
     * @return SmsoMessage
     */
    public function from(string $from): self
    {
        $this->payload['sender'] = $from;

        return $this;
    }

}
