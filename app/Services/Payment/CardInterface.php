<?php 

namespace App\Services\Payment;

interface CardInterface {
    public function makePayment(array $payload): Object;
    public function verifyPayment(array $payload): Object;
}