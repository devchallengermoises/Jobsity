<?php

namespace App\Controllers;

use App\Domain\Auth\Service\AuthUser;

use App\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    private AuthUser $authUser;


    public function __construct(AuthUser $authUser)
    {
        $this->authUser = $authUser;

    }

    // POST /login

    public function login(Request $request, Response $response): Response
    {
        $code = 200;
        $data = json_decode($request->getBody(), true);

        try {
            $res = $this->authUser->beforeCreateToken($data);

            if (!$res['success']) {
                $code = 401;
            }

        } catch (\Exception $e) {
            $response->getBody()->write($e->getMessage());
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($code);
        }

        $result = ['result' => $res];
        $response->getBody()->write(json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($code);
    }
}
