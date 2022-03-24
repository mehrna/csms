<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Charge;

use App\Domain\Charge\ChargeDetailRecord;
use App\Infrastructure\Persistence\Charge\MySqlChargeRepository;
use DI\Container;
use PDO;
use Tests\TestCase;

class MySqlChargeRepositoryTest extends TestCase
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

        $cdr = new ChargeDetailRecord(1212360, "2021-04-05T10:04:00Z", 1215230, "2021-04-05T11:27:00Z");

        $chargeRepository = new MySqlChargeRepository($pdo);
        $this->assertIsInt($chargeRepository->store($cdr, 1));
    }
}
