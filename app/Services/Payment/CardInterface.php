<?php 

namespace App\Services\Payment;

interface CardInterface {
    public function makePayment();
    public function verifyPayment();
}