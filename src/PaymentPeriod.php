<?php

namespace Maestroprog\CreditCalculator;

class PaymentPeriod
{
    private $payments = [];

    public function __construct()
    {
    }

    /**
     * @param Payment $payment
     *
     * @return void
     */
    public function addPayment(Payment $payment)
    {
        $this->payments[] = $payment;
    }

    /**
     * @return Payment[]
     */
    public function getPayments(): array
    {
        return $this->payments;
    }
}
