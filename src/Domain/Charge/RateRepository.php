<?php

declare(strict_types=1);

namespace App\Domain\Charge;

interface RateRepository
{
    public function store(Rate $rate): int;
}