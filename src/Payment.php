<?php

namespace Maestroprog\CreditCalculator;

class Payment
{
    private $date;
    private $amount;
    private $percentAmount;
    private $debtRepaymentAmount;

    public function __construct(
        \DateTimeImmutable $date,
        float $amount,
        float $percentAmount,
        float $debtRepaymentAmount
    )
    {
        $this->date = $date;
        $this->amount = $amount;
        $this->percentAmount = $percentAmount;
        $this->debtRepaymentAmount = $debtRepaymentAmount;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPercentAmount(): float
    {
        return $this->percentAmount;
    }

    public function getDebtRepaymentAmount(): float
    {
        return $this->debtRepaymentAmount;
    }
}
