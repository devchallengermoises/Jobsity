<?php

declare(strict_types=1);

namespace Tests;


use Slim\Exception\HttpUnauthorizedException;

class AuthControllerTest extends BaseTestCase
{
    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->app = $this->getAppInstance();
    }

    public function testAuthValidUser()
    {
        // Arrange

        $payload = [
            'email' => 'unit@test.com',
            'password' => '12345678'
        ];

        $request = $this->createRequest('POST', '/login');
        $request->getBody()->write((string)json_encode($payload));

        // Act
        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        // Assert
        $this->assertEquals(200, $code);
    }


    public function testAuthWrongPassword()
    {
        // Arrange

        $payload = [
            'email' => 'unit@test.com',
            'password' => '123123123'
        ];

        $request = $this->createRequest('POST', '/login');
        $request->getBody()->write((string)json_encode($payload));
        $this->expectException(HttpUnauthorizedException::class);


        // Act
        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        // Assert
        $this->assertEquals(401, $code);
    }

    public function testAuthWrongEmail()
    {
        // Arrange

        $payload = [
            'email' => 'unit2@test.com',
            'password' => '12345678'
        ];

        $request = $this->createRequest('POST', '/login');
        $request->getBody()->write((string)json_encode($payload));
        $this->expectException(HttpUnauthorizedException::class);


        // Act
        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        // Assert
        $this->assertEquals(401, $code);
    }


}
