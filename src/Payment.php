<?php

namespace Maestroprog\CreditCalculator;

class Payment
{
    private $date;
    private $amount;
    private $percentAmount;
    private $debtRepaymentAmount;
    private $countDays;
    private $prev;

    public function __construct(
        \DateTimeImmutable $prev,
        \DateTimeImmutable $date,
        float $amount,
        float $percentAmount,
        float $debtRepaymentAmount,
        int $countDays = 0
    )
    {
        $this->date = $date;
        $this->amount = $amount;
        $this->percentAmount = $percentAmount;
        $this->debtRepaymentAmount = $debtRepaymentAmount;
        $this->countDays = $countDays;
        $this->prev = $prev;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getPrev(): \DateTimeImmutable
    {
        return $this->prev;
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

    public function getCountDays(): int
    {
        return $this->countDays;
    }
}
