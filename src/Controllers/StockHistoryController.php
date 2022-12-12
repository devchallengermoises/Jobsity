<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Auth\Service\AuthUser;
use App\Domain\Client\Service\HttpClientService;
use App\Domain\Queue\Service\Producer;
use App\Domain\Stock\Repository\StockHistoryRepository;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StockHistoryController
{

    public function __construct(
        private HttpClientService      $client,
        private AuthUser               $authUser,
        private StockHistoryRepository $repository,
        private Producer               $producer,
    )
    {
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \ErrorException
     * @throws Exception
     */
    public function stock(Request $request, Response $response, array $args): Response
    {

        $data = $request->getQueryParams();

        if (!array_key_exists('q', $data)) {
            $response->getBody()->write(json_encode([
                'result' => [
                    'success' => false,
                    "data" => 'invalid stock code'
                ]
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $stock = $request->getQueryParams()['q'];

        $json = $this->client->getStockInfo($stock);

        $user = $this->authUser->getUserDataFromToken($request->getHeaders()['Authorization']);

        $json->symbols[0]->user_id = $user['user_id'];

        if (!isset($json->symbols[0]->volume)) {
            $response->getBody()->write(json_encode([
                'result' => [
                    'success' => false,
                    "data" => 'Market does not exists'
                ]
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        try {
            $this->addHistory($json->symbols[0]);
        } catch (\Exception $e) {
            throw  new Exception($e->getMessage());

        }

        unset($json->symbols[0]->volume);
        unset($json->symbols[0]->user_id);
        $body = (string)(json_encode($json->symbols[0]));

        $this->producer->produce($user['email'], $body);

        $response->getBody()->write($body);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }


    /**
     * @param Object $stock
     * @throws \ErrorException
     */

    private function addHistory(object $stock): void
    {
        if (!isset($stock)) {
            throw new \ErrorException('error');
        }
        $this->repository->create((array)$stock);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function history(Request $request, Response $response): Response
    {
        $userData = $this->authUser->getUserDataFromToken($request->getHeaders()['Authorization']);

        // todo pagination

        $users = $this->repository->findBy('user_id', $userData['user_id'])
            ?->orderBy('date', 'DESC')
            ->get()
            ->toJson();

        $response->getBody()->write(json_encode([
            'result' => [
                'success' => true,
                "data" => json_decode($users ?? '', true)
            ]
        ]));


        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);

    }

}
