<?php

declare(strict_types=1);

namespace Tests\Domain\Charge;

use App\Domain\Charge\ChargeDetailRecord;
use App\Domain\Charge\Rate;
use App\Domain\Charge\RateCalculator;
use JsonException;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionException;
use Tests\TestCase;

class RateCalculatorTest extends TestCase
{
    use ProphecyTrait;

    private object $rateCalculator;

    protected function setUp(): void
    {
        $rate = $this->prophesize(Rate::class);
        $rate->getEnergy()->willReturn(0.3);
        $rate->getTime()->willReturn(2);
        $rate->getTransaction()->willReturn(1);

        $cdr = $this->prophesize(ChargeDetailRecord::class);
        $cdr->getMeterStart()->willReturn(1204307);
        $cdr->getTimestampStart()->willReturn("2021-04-05T10:04:00Z");
        $cdr->getMeterStop()->willReturn(1215230);
        $cdr->getTimestampStop()->willReturn("2021-04-05T11:27:00Z");
        $cdr->getConsumedEnergyInWattHours()->willReturn(10000);
        $cdr->getChargeDurationInSeconds()->willReturn(3600);

        $this->rateCalculator = new RateCalculator($rate->reveal(), $cdr->reveal());
    }

    protected function tearDown(): void
    {
        $this->tearDownProphecy();
    }

    public function testGetOverall(): void
    {
        $this->assertEquals(6, $this->rateCalculator->getOverall());
    }

    public function testGetRatedEnergy(): void
    {
        $this->assertEquals(3, $this->rateCalculator->getRatedEnergy());
    }

    public function testGetRatedTime(): void
    {
        $this->assertEquals(2, $this->rateCalculator->getRatedTime());
    }

    public function testGetRatedTransaction(): void
    {
        $this->assertEquals(1, $this->rateCalculator->getRatedTransaction());
    }

    /**
     * @dataProvider RateCalculatorProvider
     * @throws JsonException
     */
    public function testJsonSerialize(): void {

        $expectedPayload = json_encode([
            'overall' => $this->rateCalculator->getOverall(),
            'components' => [
                'energy' => $this->rateCalculator->getRatedEnergy(),
                'time' => $this->rateCalculator->getRatedTime(),
                'transaction' => $this->rateCalculator->getRatedTransaction()
            ],
        ], JSON_THROW_ON_ERROR);

        $this->assertEquals($expectedPayload, json_encode($this->rateCalculator, JSON_THROW_ON_ERROR));
    }
    /**
     * @throws ReflectionException
     */
    public function testGetChargeDurationInHours(): void
    {
        $chargeDurationInHours = $this->invokeMethod($this->rateCalculator, 'getChargeDurationInHours');

        $this->assertEquals(1, $chargeDurationInHours);
    }

    private function RateCalculatorProvider(): array
    {
        return [
            [[0.3, 2, 1], [1204307, "2021-04-05T10:04:00Z", 1215230, "2021-04-05T11:27:00Z"]],
            [[0.4, 1, 2], [1305307, "2021-04-06T11:14:00Z", 1325602, "2021-04-06T13:34:00Z"]],
            [[0.5, 0.5, 1], [1402246, "2021-04-07T21:36:29Z", 1419745, "2021-04-07T23:10:00Z"]]
        ];
    }
}
