<?php

declare(strict_types=1);

namespace App\Application\Actions\Charge;

use App\Application\Actions\Action;
use App\Domain\Charge\ChargeDetailRecord;
use App\Domain\Charge\Rate;
use App\Domain\Charge\RateCalculator;
use Psr\Http\Message\ResponseInterface as Response;

class RateChargeDetailRecordAction extends Action
{
    /**
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $inputs = $this->getFormData();
        $rate = Rate::cast($inputs['rate'] ?? []);
        $cdr = ChargeDetailRecord::cast($inputs['cdr'] ?? []);
        $rateCalculator = new RateCalculator($rate, $cdr);

        return $this->respondWithData($rateCalculator);
    }
}