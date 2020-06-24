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
    /**
     * The recipient
     *
     * @param string
     */
    public $to;

    /**
     * The sender
     *
     * @param string
     */
    public $from;

    /**
     * The body of the message
     *
     * @param string
     */
    public $body;

    /**
     * Create a new Sms Message
     *
     * @param string $to
     * @param string $from
     * @param string $body
     */
    public function __construct($to, $from, $body)
    {
        $this->to = $to;
        $this->from = $from;
        $this->body = $body;
    }

    /**
     * Return the recipient
     *
     * @return string
     */
    public function to()
    {
        return $this->to;
    }

    /**
     * Return the sender
     *
     * @return string
     */
    public function from()
    {
        return $this->from;
    }

    /**
     * Return the body
     *
     * @return string
     */
    public function body()
    {
        return $this->body;
    }
}
