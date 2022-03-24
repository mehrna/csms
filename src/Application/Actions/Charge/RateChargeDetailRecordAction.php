<?php

declare(strict_types=1);

namespace App\Application\Actions\Charge;

use App\Application\Actions\Action;
use App\Domain\Charge\ChargeDetailRecord;
use App\Domain\Charge\ChargeRepository;
use App\Domain\Charge\Rate;
use App\Domain\Charge\RateCalculator;
use App\Domain\Charge\RateRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class RateChargeDetailRecordAction extends Action
{
    protected ChargeRepository $chargeRepository;
    protected RateRepository $rateRepository;

    public function __construct(LoggerInterface $logger, ChargeRepository $chargeRepository, RateRepository $rateRepository)
    {
        parent::__construct($logger);
        $this->chargeRepository = $chargeRepository;
        $this->rateRepository = $rateRepository;
    }

    /**
     * @throws \JsonException
     */
    protected function action(): Response
    {
        $inputs = $this->getFormData();

        $rate = Rate::cast($inputs['rate'] ?? []);
        $rateId = $this->rateRepository->store($rate);

        $cdr = ChargeDetailRecord::cast($inputs['cdr'] ?? []);
        $this->chargeRepository->store($cdr, $rateId);

        $rateCalculator = new RateCalculator($rate, $cdr);

        return $this->respondWithData($rateCalculator);
    }
}