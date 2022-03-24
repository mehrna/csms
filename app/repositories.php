<?php

declare(strict_types=1);

use App\Domain\Charge\RateRepository;
use App\Infrastructure\Persistence\Charge\MySqlRateRepository;
use DI\ContainerBuilder;

return static function (ContainerBuilder $containerBuilder) {
    // Here we map our RateRepository interface to its MySql implementation
    $containerBuilder->addDefinitions([
        RateRepository::class => \DI\autowire(MySqlRateRepository::class),
    ]);
};
