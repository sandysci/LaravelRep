<?php

namespace App\Services\Message;

/**
 * Class SmsMessage
 * @package App\Services\Message
 * @property string $to
 * @property string $from
 * @property string $body
 */
class SmsMessage implements Message
{

    public string $to;
    public string $from;
    public string $body;

    /**
     * Create a new Sms Message
     */
    public function __construct(string $to, string $from, string $body)
    {
        $this->to = $to;
        $this->from = $from;
        $this->body = $body;
    }

    /**
     * Return the recipient
     */
    public function to(): string
    {
        return $this->to;
    }

    /**
     * Return the sender
     */
    public function from(): string
    {
        return $this->from;
    }

    /**
     * Return the body
     *
     * @return
     */
    public function body(): string
    {
        return $this->body;
    }
}
