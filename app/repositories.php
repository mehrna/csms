<?php

declare(strict_types=1);

use App\Domain\Charge\ChargeRepository;
use App\Domain\Charge\RateRepository;
use App\Infrastructure\Persistence\Charge\MySqlChargeRepository;
use App\Infrastructure\Persistence\Charge\MySqlRateRepository;
use DI\ContainerBuilder;

return static function (ContainerBuilder $containerBuilder) {
    // Here we map our ChargeRepository and RateRepository interfaces to its MySql implementation
    $containerBuilder->addDefinitions([
        RateRepository::class => \DI\autowire(MySqlRateRepository::class),
        ChargeRepository::class => \DI\autowire(MySqlChargeRepository::class),
    ]);
};
