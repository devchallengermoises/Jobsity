<?php

declare(strict_types=1);

use Slim\App;
use Slim\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Tuupola\Middleware\JwtAuthentication;

return function (App $app) {
    $secret = $_ENV["JWT_SECRET"];

    $app->add(new JwtAuthentication([
        "path" => ["/bye", "/stock", "/history"],
        "ignore" => ["/users", "/login", "/hello"],
        'secret' => $secret,
        "error" => function ($response) {
            return $response->withStatus(401);
        }
    ]));

    // 2nd middleware to throw 401 with correct slim exception
    // Reformat when lin updates to v4, see: https://github.com/tuupola/slim-basic-auth/issues/95
    $app->add(function (Request $request, RequestHandler $handler) {
        $response = $handler->handle($request);
        $statusCode = $response->getStatusCode();

        if ($statusCode == 401) {
            throw new HttpUnauthorizedException($request);
        }

        return $response;
    });
};
