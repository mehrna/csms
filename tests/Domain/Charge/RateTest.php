<?php

declare(strict_types=1);

namespace Tests\Domain\Charge;

use App\Domain\Charge\Rate;
use InvalidArgumentException;
use Tests\TestCase;

class RateTest extends TestCase
{
    public function RateProvider(): array
    {
        return [
            [0.3, 2, 1],
            [0.5, 0.5, 1],
        ];
    }

    /**
     * @dataProvider RateProvider
     */
    public function testGetters(float $energy, float $time, float $transaction): void
    {
        $rate = new Rate($energy, $time, $transaction);

        $this->assertEquals($energy, $rate->getEnergy());
        $this->assertEquals($time, $rate->getTime());
        $this->assertEquals($transaction, $rate->getTransaction());
    }

    /**
     * @dataProvider RateProvider
     */
    public function testCast(float $energy, float $time, float $transaction): void
    {
        $expectedCDR = new Rate($energy, $time, $transaction);
        $cdr = Rate::cast($this->getProvidedData());

        $this->assertEquals($expectedCDR, $cdr);
    }

    public function ExceptionRateProvider(): array
    {
        return [
            [0],
            [-1]
        ];
    }

    /**
     * @dataProvider ExceptionRateProvider
     */
    public function testEnergyWithValueLessThanOrEqualToZeroThrowsException(float $energy): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Rate($energy, 2, 1);
    }

    /**
     * @dataProvider ExceptionRateProvider
     */
    public function testTimeWithValueLessThanOrEqualToZeroThrowsException(float $time): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Rate(0.3, $time, 1);
    }

    /**
     * @dataProvider ExceptionRateProvider
     */
    public function testTransactionWithValueLessThanOrEqualToZeroThrowsException(float $transaction): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Rate(0.3, 2, $transaction);
    }
}
