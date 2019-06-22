<?php

namespace Maestroprog\CreditCalculator;

class Period
{
    const MONTH_SECONDS = 2592000;

    private $from;
    private $to;
    private $continueFrom;

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

    /**
     * @return \Generator|Period[]
     */
    public function each(): \Generator
    {
        $baseDate = (int)$this->from->format('d');
        $current = $this->continueFrom ?? $this->from;
        $currentNormal = $this->from;
        $prev = $current;
        while ($current < $this->to) {
            $year = (int)$currentNormal->format('Y');
            $month = (int)$currentNormal->format('m');

            if ($month === 12) {
                $year++;
                $month = 0;
            }
            $month = $month + 1;

            $next = new \DateTimeImmutable(sprintf('%04d-%02d-%02d', $year, $month, $baseDate));
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
            if ((int)$next->format('d') < $baseDate) {
                $next->modify('1 day');
            }

            $day = $next->format('N');
            if ($day >= 7 || ($day >= 6 && cal_days_in_month(CAL_GREGORIAN, $month, $year) > 29)) {
                $next = $next->modify((8 - $day) . ' day');
            }
            yield new self($current, $next);

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
