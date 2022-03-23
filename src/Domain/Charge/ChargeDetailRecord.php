<?php

declare(strict_types=1);

namespace App\Domain\Charge;

use DateTime;
use InvalidArgumentException;

class ChargeDetailRecord
{
    final public function __construct(
        private int $meterStart,
        private string $timestampStart,
        private int $meterStop,
        private string $timestampStop
    ) {
        if ($this->meterStart < 0) {
            throw new InvalidArgumentException('meterStart needs to be >= 0');
        }

        if ($this->meterStop <= $this->meterStart) {
            throw new InvalidArgumentException('meterStop needs to be > meterStart');
        }

        if (DateTime::createFromFormat(DATE_ATOM, $this->timestampStart) === false) {
            throw new InvalidArgumentException('timestampStart needs to be valid datetime - ATOM (ISO-8601) format');
        }

        if (DateTime::createFromFormat(DATE_ATOM, $this->timestampStop) === false) {
            throw new InvalidArgumentException('timestampStop needs to be valid datetime - ATOM (ISO-8601) format');
        }
    }

    public function getMeterStart(): int
    {
        return $this->meterStart;
    }

    public function getTimestampStart(): string
    {
        return $this->timestampStart;
    }

    public function getMeterStop(): int
    {
        return $this->meterStop;
    }

    public function getTimestampStop(): string
    {
        return $this->timestampStop;
    }

    public function getConsumedEnergyInWattHours(): int
    {
        return $this->meterStop - $this->meterStart;
    }

    public function getChargeDurationInSeconds(): int
    {
        return strtotime($this->timestampStop) - strtotime($this->timestampStart);
    }

    public static function cast(array $cdrData): ChargeDetailRecord
    {
        return new static(...$cdrData);
    }
}