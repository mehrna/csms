<?php

declare(strict_types=1);

namespace Tests\Domain\Charge;

use App\Domain\Charge\ChargeDetailRecord;
use InvalidArgumentException;
use Tests\TestCase;

class ChargeDetailRecordTest extends TestCase
{
    public function ChargeDetailRecordProvider(): array
    {
        return [
            [1204307, "2021-04-05T10:04:00Z", 1215230, "2021-04-05T11:27:00Z"],
            [1305307, "2021-04-06T11:14:00Z", 1325602, "2021-04-06T13:34:00Z"],
            [1402246, "2021-04-07T21:36:29Z", 1419745, "2021-04-07T23:10:00Z"],
        ];
    }

    /**
     * @dataProvider ChargeDetailRecordProvider
     */
    public function testGetters(int $meterStart, string $timestampStart, int $meterStop, string $timestampStop): void
    {
        $cdr = new ChargeDetailRecord($meterStart, $timestampStart, $meterStop, $timestampStop);

        $this->assertEquals($meterStart, $cdr->getMeterStart());
        $this->assertEquals($timestampStart, $cdr->getTimestampStart());
        $this->assertEquals($meterStop, $cdr->getMeterStop());
        $this->assertEquals($timestampStop, $cdr->getTimestampStop());
    }

    /**
     * @dataProvider ChargeDetailRecordProvider
     */
    public function testGetConsumedWattHours(): void
    {
        $cdr = ChargeDetailRecord::cast($this->getProvidedData());
        $expected = $this->getProvidedData()[2] - $this->getProvidedData()[0];
        $this->assertEquals($expected, $cdr->getConsumedEnergyInWattHours());
    }

    /**
     * @dataProvider ChargeDetailRecordProvider
     */
    public function testGetDurationInSeconds(): void
    {
        $cdr = ChargeDetailRecord::cast($this->getProvidedData());
        $expected = strtotime($this->getProvidedData()[3]) - strtotime($this->getProvidedData()[1]);
        $this->assertEquals($expected, $cdr->getChargeDurationInSeconds());
    }

    /**
     * @dataProvider ChargeDetailRecordProvider
     */
    public function testCast(int $meterStart, string $timestampStart, int $meterStop, string $timestampStop): void
    {
        $expectedCDR = new ChargeDetailRecord($meterStart, $timestampStart, $meterStop, $timestampStop);
        $cdr = ChargeDetailRecord::cast($this->getProvidedData());

        $this->assertEquals($expectedCDR, $cdr);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testMeterStartWithZeroValueIsOkay(): void
    {
        new ChargeDetailRecord(0, "2021-04-05T10:04:00Z", 1215230, "2021-04-05T11:27:00Z");
    }

    public function testMeterStartWithNegativeValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChargeDetailRecord(-1, "2021-04-05T10:04:00Z", 1215230, "2021-04-05T11:27:00Z");
    }

    public function testMeterStopWithValueLessThanMeterStartThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChargeDetailRecord(1204307, "2021-04-05T10:04:00Z", 1204300, "2021-04-05T11:27:00Z");
    }

    public function testMeterStopWithValueEqualToMeterStartThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ChargeDetailRecord(1204307, "2021-04-05T10:04:00Z", 1204307, "2021-04-05T11:27:00Z");
    }
}
