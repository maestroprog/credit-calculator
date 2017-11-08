<?php

namespace Maestroprog\CreditCalculator;

class Payment
{
    public function __construct(
        \DateTimeImmutable $date,
        float $amount,
        float $percentAmount,
        float $debtRepaymentAmount
    ) {
    }
}
