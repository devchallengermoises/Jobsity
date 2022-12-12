<?php

declare(strict_types=1);

namespace Tests;

use Slim\Exception\HttpUnauthorizedException;

class StockControllerTest extends BaseTestCase
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


    public function testStockEndpoint()
    {
        // Arrange

        $request = $this->createRequest('GET', '/stock', [
            'Authorization' => "Bearer {$this->getAuthorizationHeader()}"
        ], 'q=tsla.us');

        //Act

        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        //Assert

        $this->assertEquals(200, $code);
    }

    public function testStockWithoutAuth()
    {
        // Arrange
        $request = $this->createRequest('GET', '/stock', [], 'q=googl.us');
        $this->expectException(HttpUnauthorizedException::class);

        //Act
        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        //Assert
        $this->assertEquals(401, $code);
    }

    public function testHistoryWithAuth()
    {
        // Arrange
        $headers = ['Authorization' => 'Bearer ' . $this->getAuthorizationHeader()];
        $request = $this->createRequest('GET', '/history', $headers);


        //Act
        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        //Assert
        $this->assertEquals(200, $code);
    }


    public function testHistoryWithoutAuth()
    {
        // Arrange
        $request = $this->createRequest('GET', '/history');
        $this->expectException(HttpUnauthorizedException::class);


        //Act
        $response = $this->app->handle($request);
        $code = $response->getStatusCode();

        //Assert
        $this->assertEquals(401, $code);
    }

}
