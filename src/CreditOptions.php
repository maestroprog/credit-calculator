<?php

namespace Maestroprog\CreditCalculator;

class CreditOptions
{
    private $interestFreeDays;

    public function __construct(int $interestFreeDays)
    {
        $this->interestFreeDays = $interestFreeDays;
    }

    public function getInterestFreeDays(): int
    {
        return $this->interestFreeDays;
    }
}
