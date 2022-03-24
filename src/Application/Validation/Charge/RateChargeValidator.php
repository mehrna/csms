<?php

declare(strict_types=1);

namespace App\Application\Validation\Charge;

use App\Application\Validation\Validator;
use Webmozart\Assert\Assert;

class RateChargeValidator extends Validator
{
    /**
     * @param array $data
     */
    public function validationRules(array $data): void
    {
        // Rate
        Assert::keyExists($data, 'rate');

        Assert::keyExists($data['rate'], 'energy', "Expected the key 'energy' to exist under 'rate'.");
        Assert::keyExists($data['rate'], 'time', "Expected the key 'time' to exist under 'rate'.");
        Assert::keyExists($data['rate'], 'transaction', "Expected the key 'transaction' to exist under 'rate'.");

        Assert::notEmpty($data['rate']['energy'], "Expected a non-empty value on 'energy' Got: ''.");
        Assert::notEmpty($data['rate']['time'], "Expected a non-empty value on 'time' Got: ''.");
        Assert::notEmpty($data['rate']['transaction'], "Expected a non-empty value on 'time' Got: ''.");

        Assert::scalar($data['rate']['energy']);
        Assert::scalar($data['rate']['time']);
        Assert::scalar($data['rate']['transaction']);

        // CDR
        Assert::keyExists($data, 'cdr');

        Assert::keyExists($data['cdr'], 'meterStart', "Expected the key 'meterStart' to exist under 'cdr'.");
        Assert::keyExists($data['cdr'], 'timestampStart', "Expected the key 'timestampStart' to exist under 'cdr'.");
        Assert::keyExists($data['cdr'], 'meterStop', "Expected the key 'meterStop' to exist under 'cdr'.");
        Assert::keyExists($data['cdr'], 'timestampStop', "Expected the key 'timestampStop' to exist under rate.");

        Assert::notEmpty($data['cdr']['meterStart'], "Expected a non-empty value on 'meterStart' Got: ''.");
        Assert::notEmpty($data['cdr']['timestampStart'], "Expected a non-empty value on 'timestampStart' Got: ''.");
        Assert::notEmpty($data['cdr']['meterStop'], "Expected a non-empty value on 'meterStop' Got: ''.");
        Assert::notEmpty($data['cdr']['timestampStop'], "Expected a non-empty value on 'timestampStop' Got: ''.");

        Assert::notSame(
            $data['cdr']['meterStart'],
            $data['cdr']['meterStop'],
            "Expected 'meterStart' value not identical to 'meterStop'."
        );
        Assert::notSame(
            $data['cdr']['timestampStart'],
            $data['cdr']['timestampStop'],
            "Expected 'timestampStart' value not identical to 'timestampStop'."
        );

        Assert::greaterThanEq($data['cdr']['meterStart'], 0);
        Assert::greaterThan($data['cdr']['meterStop'], $data['cdr']['meterStart']);
        Assert::greaterThan($data['cdr']['timestampStop'], $data['cdr']['timestampStart']);

        Assert::regex(
            $data['cdr']['timestampStart'],
            '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/',
            "The timestampStart value %s is not an expected datetime and need to be formatted as 'Y-m-dTH:i:sZ' (ISO 8601)"
        );
        Assert::regex(
            $data['cdr']['timestampStop'],
            '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/',
            "The timestampStop value %s is not an expected datetime and need to be formatted as 'Y-m-dTH:i:sZ' (ISO 8601)"
        );
    }
}