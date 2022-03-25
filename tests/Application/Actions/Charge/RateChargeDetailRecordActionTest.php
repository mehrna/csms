<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Charge;

use App\Application\Actions\ActionPayload;
use Tests\TestCase;

class RateChargeDetailRecordActionTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testAction(): void
    {
        $app = $this->getAppInstance();

        $data = [
            'rate' => [
                'energy' => 0.3,
                'time' => 2,
                'transaction' => 1
            ],
            'cdr' => [
                'meterStart' => 1204307,
                'timestampStart' => '2021-04-05T10:04:00Z',
                'meterStop' => 1215230,
                'timestampStop' => '2021-04-05T11:27:00Z'
            ]
        ];

        $request = $this->createRequest('POST', '/rate')->withParsedBody($data);
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = [
            'overall' => 7.04,
            'components' => ['energy' => 3.277, 'time' => 2.767, 'transaction' => 1],
        ];
        $serializedPayload = json_encode($expectedPayload, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
