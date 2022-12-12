<?php

declare(strict_types=1);

namespace Tests;
use Faker\Factory;

class UserControllerTest extends BaseTestCase
{
    /**
     * @var \Slim\App
     */
    protected $app;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->app = $this->getAppInstance();
    }

    public function testUserCreateValid()
    {
        //Arr
        $faker = Factory::create();

        $payload = [
            'email' => $faker->email(),
            'password' => '12345678'
        ];

        $request = $this->createRequest('POST', '/users');
        $request->getBody()->write((string)json_encode($payload));

        // Act
        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        // Assert
        $this->assertEquals(201, $code);
    }

    public function testUserCreatWithoutEmail()
    {
        $payload = [
            'password' => '12345678'
        ];

        $request = $this->createRequest('POST', '/users');
        $request->getBody()->write((string)json_encode($payload));

        // Act
        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        // Assert
        $this->assertEquals(400, $code);
    }

    public function testUserCreatWithoutPassword()
    {
        // Arrange

        $faker = Factory::create();

        $payload = [
            'email' => $faker->email(),
        ];

        $request = $this->createRequest('POST', '/users');
        $request->getBody()->write((string)json_encode($payload));

        // Act
        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        // Assert
        $this->assertEquals(400, $code);
    }

}
