<?php

namespace App\Services\Message;

interface Message
{
    /**
     * Return the recipient
     *
     * @return string
     */
    public function to();

    /**
     * Return the sender
     *
     * @return string
     */
    public function from();

    /**
     * Return the body
     *
     * @return string
     */
    public function body();
}
