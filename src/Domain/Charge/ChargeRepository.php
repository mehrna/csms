<?php

declare(strict_types=1);

namespace App\Domain\Charge;

interface ChargeRepository
{
    public function store(ChargeDetailRecord $cdr, int $id): int;
}