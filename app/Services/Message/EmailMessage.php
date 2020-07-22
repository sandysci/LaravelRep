<?php

namespace App\Services\Message;

class EmailMessage
{
    /**
     * The recipient
     *
     * @param string
     */
    private $to;

    /**
     * The sender
     *
     * @param string
     */
    private $from;

    /**
     * The subject
     *
     * @param string
     */
    private $subject;

    /**
     * The body of the message
     *
     * @param
     */
    private $body;

    /**
     * @var
     */
    private $cc;

    /**
     * @var
     */
    private $bcc;

    /**
     * Create a new Email Message
     *
     * @param string $to
     * @param string $subject
     * @param  $body
     * @param string cc
     * @param string $from
     * @param string $bcc
     */
    public function __construct(string $to, string $subject, $body, ?string $cc, ?string $from, ?string $bcc)
    {
        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->body = $body;
        $this->cc = $cc ?? "";
        $this->bcc = $bcc ?? "";
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
     * Return the subject
     *
     * @return string
     */
    public function subject()
    {
        return $this->subject;
    }

    /**
     * Return the body
     *
     * @return
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * Return the cc
     *
     * @return string
     */
    public function cc()
    {
        return $this->cc;
    }

    /**
     * Return the bcc
     *
     * @return string
     */
    public function bcc()
    {
        return $this->bcc;
    }
}
