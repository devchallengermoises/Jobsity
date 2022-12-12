<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HelloController;
use App\Controllers\StockHistoryController;
use App\Controllers\UserController;
use Slim\App;

return function (App $app) {
    // unprotected routes
    $app->get('/hello/{name}', HelloController::class . ':hello');

    // protected routes
    $app->get('/bye/{name}', HelloController::class . ':bye');
    $app->get('/stock', StockHistoryController::class . ':stock');
    $app->get('/history', StockHistoryController::class . ':history');
    $app->post('/users', UserController::class . ':create');
    $app->post('/login', AuthController::class . ':login');

};
