<?php

namespace Maestroprog\CreditCalculator;

class PaymentPeriod
{
    /**
     * @var Payment[]
     */
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

    /**
     * Сумма переплаты
     *
     * @return float
     */
    public function getOverPay(): float
    {
        $result = 0;
        /** @var Payment $payment */
        foreach ($this->payments as $payment) {
            $result += $payment->getPercentAmount();
        }

        return $result;
    }
}
