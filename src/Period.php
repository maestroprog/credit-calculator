<?php

namespace Maestroprog\CreditCalculator;

class Period
{
    const MONTH_SECONDS = 2592000;

    private $from;
    private $to;

    public function __construct(\DateTimeImmutable $from, \DateTimeImmutable $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function getFrom(): \DateTimeImmutable
    {
        return $this->from;
    }

    public function getTo(): \DateTimeImmutable
    {
        return $this->to;
    }

    public function getMonthCount(): int
    {
        return ceil(($this->to->getTimestamp() - $this->from->getTimestamp()) / self::MONTH_SECONDS);
    }
}
