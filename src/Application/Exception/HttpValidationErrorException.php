<?php

namespace App\Application\Exception;

use Slim\Exception\HttpSpecializedException;

class HttpValidationErrorException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 422;

    /**
     * @var string
     */
    protected $message = 'Validation Error.';

    protected $title = '422 Validation Error';
    protected $description = 'Validation Failed!';
}