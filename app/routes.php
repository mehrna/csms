<?php

declare(strict_types=1);

use App\Application\Actions\Charge\RateChargeDetailRecordAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return static function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('It works!');
        return $response;
    });

    $app->post('/rate', RateChargeDetailRecordAction::class);
};
