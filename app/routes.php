<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HelloController;
use App\Controllers\StockHistoryController;
use App\Controllers\UserController;
use Slim\App;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function (App $app) {
    // Root endpoint
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'API is running',
            'data' => [
                'version' => '1.0.0',
                'timestamp' => date('c'),
                'environment' => $_ENV['ENV'] ?? 'development'
            ]
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });


    // protected routes
    $app->get('/stock', StockHistoryController::class . ':stock');
    $app->get('/history', StockHistoryController::class . ':history');
    $app->post('/user', UserController::class . ':create');
    $app->post('/login', AuthController::class . ':login');

};
