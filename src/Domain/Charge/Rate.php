<?php

declare(strict_types=1);

namespace App\Domain\Charge;

use InvalidArgumentException;

class Rate
{
    final public function __construct(
        private float $energy,
        private float $time,
        private float $transaction
    ) {
        if ($this->energy <= 0) {
            throw new InvalidArgumentException('energy needs to be > 0');
        }
        if ($this->time <= 0) {
            throw new InvalidArgumentException('time needs to be > 0');
        }
        if ($this->transaction <= 0) {
            throw new InvalidArgumentException('transaction needs to be > 0');
        }
    }

    public function getEnergy(): float
    {
        return $this->energy;
    }

    public function getTime(): float
    {
        return $this->time;
    }

    public function getTransaction(): float
    {
        return $this->transaction;
    }

    public static function cast(array $rateData): Rate
    {
        return new static(...$rateData);
    }
}