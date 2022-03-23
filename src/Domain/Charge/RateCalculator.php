<?php

declare(strict_types=1);

namespace App\Domain\Charge;

use JsonSerializable;

class RateCalculator implements JsonSerializable
{
    public const OVERALL_PRECISION = 2;
    public const COMPONENT_PRECISION = 3;

    public function __construct(
        private Rate $rate,
        private ChargeDetailRecord $cdr
    ) {
    }

    public function getOverall(): float
    {
        $overall = $this->getRatedEnergy() + $this->getRatedTime() + $this->getRatedTransaction();

        return round($overall, self::OVERALL_PRECISION);
    }

    public function getRatedEnergy(): float
    {
        $ratedEnergy = ($this->cdr->getConsumedEnergyInWattHours() * $this->rate->getEnergy()) / 1000;

        return round($ratedEnergy, self::COMPONENT_PRECISION);
    }

    public function getRatedTime(): float
    {
        $ratedTime = $this->getChargeDurationInHours() * $this->rate->getTime();

        return round($ratedTime, self::COMPONENT_PRECISION);
    }

    public function getRatedTransaction(): float
    {
        return round($this->rate->getTransaction(), self::COMPONENT_PRECISION);
    }

    public function jsonSerialize(): array
    {
        return [
            'overall' => $this->getOverall(),
            'components' => [
                'energy' => $this->getRatedEnergy(),
                'time' => $this->getRatedTime(),
                'transaction' => $this->getRatedTransaction()
            ],
        ];
    }

    private function getChargeDurationInHours(): float
    {
        return ($this->cdr->getChargeDurationInSeconds() / 60) / 60;
    }
}