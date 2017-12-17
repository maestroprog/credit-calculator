<?php

namespace Maestroprog\CreditCalculator;

use Composer\Installer\PearInstaller;

class Period
{
    const MONTH_SECONDS = 2592000;

    private $from;
    private $to;

    public function __construct(\DateTimeImmutable $from, \DateTimeImmutable $to)
    {
        $this->from = $from;
        $this->to = $to;
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
        $current = $this->from;
        while ($current < $this->to) {
            yield new self($current, $current = $current->modify('1 month'));
        }
    }
}
