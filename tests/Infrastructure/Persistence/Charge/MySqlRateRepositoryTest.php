<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Charge;

use App\Domain\Charge\ChargeDetailRecord;
use App\Domain\Charge\Rate;
use App\Infrastructure\Persistence\Charge\MySqlChargeRepository;
use App\Infrastructure\Persistence\Charge\MySqlRateRepository;
use DI\Container;
use PDO;
use Tests\TestCase;

class MySqlRateRepositoryTest extends TestCase
{
    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Exception
     */
    public function testStore(): void
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();
        $pdo = $container->get(PDO::class);

        $rate = new Rate(0.3, 2, 1);

        $chargeRepository = new MySqlRateRepository($pdo);
        $this->assertIsInt($chargeRepository->store($rate));
    }
}
