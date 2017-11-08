<?php

namespace Maestroprog\CreditCalculator;

class Calculator
{
    private $credit;
    private $percent;
    private $period;

    public function __construct(int $credit, float $percent, Period $period)
    {
        $this->credit  = $credit;
        $this->percent = $percent;
        $this->period  = $period;
    }

    /**
     * Рассчитать график платежей при неизменяющеся сумме платежа.
     *
     * @param int $monthlyPay
     *
     * @return PaymentPeriod
     */
    public function paymentSchedule(int $monthlyPay): PaymentPeriod
    {
        $period = new PaymentPeriod();


    }

    /**
     * Рассчитать график платежей на определённый период.
     *
     * @param Period $period
     * @param int    $monthlyPay
     *
     * @return PaymentPeriod
     */
    public function paymentScheduleShortPeriod(Period $period, int $monthlyPay): PaymentPeriod
    {

    }

    /**
     * Применяет период платежей к данному калькулятору для вычисления остатка задолженности.
     *
     * @param PaymentPeriod $period
     *
     * @return void
     */
    public function applyPaymentPeriod(PaymentPeriod $period)
    {

    }
}
