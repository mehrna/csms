<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Charge;

use App\Domain\Charge\Rate;
use App\Domain\Charge\RateRepository;
use PDO;

class MySqlRateRepository implements RateRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function store(Rate $rate): int
    {
        $row = [
            'energyRate' => $rate->getEnergy(),
            'timeRate' => $rate->getTime(),
            'transactionRate' => $rate->getTransaction(),
        ];

        $sql = "INSERT INTO `rates` SET 
                `energy`=:energyRate, 
                `time`=:timeRate, 
                `transaction`=:transactionRate;";

        $this->connection->prepare($sql)->execute($row);

        return (int)$this->connection->lastInsertId();
    }
}
