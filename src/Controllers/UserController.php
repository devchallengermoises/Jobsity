<?php

namespace App\Controllers;

use App\Domain\User\Service\ValidateUser;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response as Response;

class UserController
{
    private ValidateUser $validator;

    public function __construct(ValidateUser $validator)
    {
        $this->validator = $validator;

    }

    // POST /users

    /**
     * @param Request $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return Response
     */
    public function create(Request $request, Response $response): Response
    {
        $code = 201;
        $data = json_decode($request->getBody(), true);

        try {
            $res = $this->validator->create($data);

            if (!$res['success']) {
                $code = 400;
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
