<?php

namespace Maestroprog\CreditCalculator;

class Period
{
    const MONTH_SECONDS = 2592000;

    private $from;
    private $to;
    private $continueFrom;
    private $payFrom;

    public function __construct(\DateTimeImmutable $from, \DateTimeImmutable $to, \DateTimeImmutable $continueFrom = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->continueFrom = $continueFrom;
    }

    public function getFrom(): \DateTimeImmutable
    {
        return $this->from;
    }

    public function getTo(): \DateTimeImmutable
    {
        return $this->to;
    }

    public function getPayFrom(): \DateTimeImmutable
    {
        return $this->payFrom;
    }

    public function setPayFrom(?\DateTimeImmutable $payFrom)
    {
        $this->payFrom = $payFrom;

        return $this;
    }

    /**
     * @return \Generator|Period[]
     */
    public function each(): \Generator
    {
        $baseDate = $this->from;
        $baseDay = (int)($this->payFrom ?? $this->from)->format('d');
        $current = $this->continueFrom ?? $this->from;
        $currentNormal = $baseDate;
        $prev = $current;
        while ($current < $this->to) {
            if ($this->payFrom) {
                $next = clone $this->payFrom;

                $year = (int)$next->format('Y');
                $month = (int)$next->format('m');
            } else {
                $year = (int)$currentNormal->format('Y');
                $month = (int)$currentNormal->format('m');

                if ($month === 12) {
                    $year++;
                    $month = 0;
                }
                $month = $month + 1;
                $next = new \DateTimeImmutable(sprintf('%04d-%02d-%02d', $year, $month, $baseDay));
            }
            if ((int)$next->format('m') !== ($month)) {
                $next = $next->modify('-1 day');
            }
            if ((int)$next->format('m') !== ($month)) {
                $next = $next->modify('-1 day');
            }
            if ((int)$next->format('m') !== ($month)) {
                throw new \LogicException('LOGIC error!');
            }
            $nextNormal = $next;
            if ((int)$next->format('d') < $baseDay) {
                $next->modify('1 day');
            }

            $day = $next->format('N');
            if ($day >= 7 || ($day >= 6 && cal_days_in_month(CAL_GREGORIAN, $month, $year) > 29)) {
                $next = $next->modify((8 - $day) . ' day');
            }
            yield (new self($current, $next));
            $this->payFrom = null;

            $prev = $current;
            $currentNormal = $nextNormal;
            $current = $next;
        }
    }

    public function getMonthCount(): int
    {
        return floor(($this->to->getTimestamp() - $this->from->getTimestamp()) / self::MONTH_SECONDS);
    }

    public function __toString()
    {
        return sprintf('%s - %s', $this->from->format('Y-m-d'), $this->to->format('Y-m-d'));
    }
}
