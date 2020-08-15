<?php

namespace App\Services\Message;

interface Message
{
    /**
     * Return the recipient
     *
     * @return string
     */
    public function to(): string;

    /**
     * Return the sender
     *
     * @return string
     */
    public function from(): ?string;

    /**
     * Return the body
     */
    public function body();
}
