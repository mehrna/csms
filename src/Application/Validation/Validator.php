<?php

declare(strict_types=1);

namespace App\Application\Validation;

use App\Application\Exception\ValidationException;
use InvalidArgumentException;

abstract class Validator
{
    /**
     * @throws ValidationException
     */
    public function validate(array $data): void
    {
        try {
            $this->validationRules($data);
        } catch (InvalidArgumentException $exception) {
            throw new ValidationException($exception->getMessage());
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    abstract public function validationRules(array $data): void;
}