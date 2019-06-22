<?php

namespace Maestroprog\CreditCalculator;

class Calculator
{
    private $credit;
    private $percent;
    private $period;
    private $creditOptions;
    private $interestFreeDays;

    public function __construct(int $credit, float $percent, Period $period, CreditOptions $creditOptions)
    {
        $this->credit = $credit;
        $this->percent = $percent;
        $this->period = $period;
        $this->creditOptions = $creditOptions;
        $this->interestFreeDays = $this->creditOptions->getInterestFreeDays();
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
        $credit = $this->credit;
        foreach ($this->period->each() as $nextPeriod) {
            if ($credit <= 0) {
                break;
            }
            // итерируем пока не прошли 60 месяцев и пока кредит больше 0
            $percentYearAmount = $credit * $this->percent / 100; // проценты набежавшие за год
            $currentMonth = $nextPeriod->getFrom();
            $nextMonth = $nextPeriod->getTo();
            $days = ($nextMonth->getTimestamp() - $currentMonth->getTimestamp()) / 86400;
            $daysWithoutPercent = min($days, $this->interestFreeDays);
            $this->interestFreeDays -= $daysWithoutPercent;
            $days -= $daysWithoutPercent;

            $percentMonthAmount = $percentYearAmount / 365 * $days; // проценты набежавшие за месяц

            $percentMonthAmount = round($percentMonthAmount, 2);

            $payment = min($monthlyPay, $credit + $percentMonthAmount);

            $paymentObject = new Payment(
                $currentMonth,
                $nextMonth,
                $payment,
                $percentMonthAmount,
                $payment - $percentMonthAmount
            );

            $credit -= ($payment - $percentMonthAmount); // вычитаем из суммы долга платеж без учета процентов

            $period->addPayment($paymentObject);
        }

        return $period;
    }

    /**
     * Рассчитать график платежей на определённый период.
     *
     * @param Period $paymentPeriod
     * @param int $monthlyPay
     *
     * @return PaymentPeriod
     */
    public function paymentScheduleShortPeriod(Period $paymentPeriod, int $monthlyPay): PaymentPeriod
    {
        $period = new PaymentPeriod();
        $credit = $this->credit;
        $daysInYear = date('L') ? 366 : 365;
        $daysInYear = 365;
        foreach ($paymentPeriod->each() as $nextPeriod) {
            if ($credit <= 0) {
                break;
            }
            // итерируем пока не прошли 60 месяцев и пока кредит больше 0
            $percentYearAmount = $credit * $this->percent / 100; // проценты набежавшие за год
            $currentMonth = $nextPeriod->getFrom();
            $nextMonth = $nextPeriod->getTo();
            $days = ($nextMonth->getTimestamp() - $currentMonth->getTimestamp()) / 86400;
            $daysWithoutPercent = min($days, $this->interestFreeDays);
            $this->interestFreeDays -= $daysWithoutPercent;
            $days -= $daysWithoutPercent;

            $percentMonthAmount = $percentYearAmount / $daysInYear * $days; // проценты набежавшие за месяц
            $payment = min($monthlyPay, $credit + $percentMonthAmount);
            $paymentObject = new Payment(
                $currentMonth,
                $nextMonth,
                $payment,
                $percentMonthAmount,
                $payment - $percentMonthAmount,
                $days
            );
            $credit -= ($payment - $percentMonthAmount); // вычитаем из суммы долга платеж без учета процентов
            $period->addPayment($paymentObject);
        }

        return $period;
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
        foreach ($period->getPayments() as $payment) {
            $this->credit -= $payment->getDebtRepaymentAmount();
        }
        if (isset($payment)) {
            $this->period = new Period($payment->getDate(), $this->period->getTo());
        }
    }

    public function getCredit(): float
    {
        return $this->credit;
    }

    public function getMinimalPayment($credit, Period $period): float
    {
        $rate = $this->percent / 100;

        $minimalPayment = round($credit * $rate / (12 * (1 - (1 + $rate / 12) ** -$period->getMonthCount())));

        $i = 10 ** (strlen($minimalPayment) - 2);

        return round($minimalPayment / $i) * $i;
    }
}
