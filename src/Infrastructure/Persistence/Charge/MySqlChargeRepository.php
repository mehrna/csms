<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Charge;

use App\Domain\Charge\ChargeDetailRecord;
use App\Domain\Charge\ChargeRepository;
use PDO;

class MySqlChargeRepository implements ChargeRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function store(ChargeDetailRecord $cdr, int $id): int
    {
        $row = [
            'rateId' => $id,
            'meterStart' => $cdr->getMeterStart(),
            'timestampStart' => $cdr->getTimestampStart(),
            'meterStop' => $cdr->getMeterStop(),
            'timestampStop' => $cdr->getTimestampStop(),
        ];

        $sql = "INSERT INTO `charge_detail_records` SET 
                `rate_id`=:rateId,
                `meter_start`=:meterStart,
                `timestamp_start`=STR_TO_DATE(:timestampStart, '%Y-%m-%dT%H:%i:%sZ'), 
                `meter_stop`=:meterStop,
                `timestamp_stop`=STR_TO_DATE(:timestampStop, '%Y-%m-%dT%H:%i:%sZ');";

        $this->connection->prepare($sql)->execute($row);

        return (int)$this->connection->lastInsertId();
    }
}
