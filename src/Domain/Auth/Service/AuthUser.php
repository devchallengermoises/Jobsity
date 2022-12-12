<?php

namespace App\Domain\Auth\Service;

use App\Domain\User\User;
use Exception;
use Firebase\JWT\JWT;
use App\Domain\User\Service\ValidateUser;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthUser
{

    public function __construct(
        private ValidateUser $validator
    )
    {
    }

    /**
     * @throws Exception
     */
    public function beforeCreateToken($data): array
    {
        $validate = $this->validator->login($data);

        if ($validate['success']) {
            return [
                'success' => true,
                'data' => $this->createToken($validate['data'])
            ];
        }
        return [
            'success' => false,
            'data' => $validate['data']
        ];

    }

    private function createToken(User $user): string
    {
        $expires = new \DateTime("+60 minutes");
        $payload = [
            "iat" => (new \DateTime())->getTimeStamp(),
            "exp" => $expires->getTimeStamp(),
            "sub" => ['user_id' => $user->id, 'email' => $user->email]
        ];
        return JWT::encode($payload, $_ENV["JWT_SECRET"]);

    }


    /**
     * @param array $headers
     * @return array
     */
    public function getUserDataFromToken(array $headers): array
    {
        $string = implode($headers);
        $fragment = explode(" ", $string);
        $jwt = $fragment[1];

        $decoded = JWT::decode($jwt, new Key($_ENV["JWT_SECRET"], 'HS256'));

        return (array)$decoded->sub;
    }

}
