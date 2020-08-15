<?php

namespace App\Services\Message;

class EmailMessage
{

    private string $to;
    private ?string $from;
    private string $subject;
    private $body;
    private ?string $cc;
    private ?string $bcc;

    /**
     * Create a new Email Message
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
     */
    public function to(): string
    {
        return $this->to;
    }

    /**
     * Return the sender
     */
    public function from(): ?string
    {
        return $this->from;
    }

    /**
     * Return the subject
     */
    public function subject(): string
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
     */
    public function cc(): ?string
    {
        return $this->cc;
    }

    /**
     * Return the bcc
     */
    public function bcc(): ?string
    {
        return $this->bcc;
    }
}
