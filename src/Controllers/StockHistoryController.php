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
                'status' => 'error',
                'message' => 'Invalid stock code',
                'data' => null
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
                'status' => 'error',
                'message' => 'Market does not exist',
                'data' => null
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
        $stockData = $json->symbols[0];

        $this->producer->produce($user['email'], json_encode($stockData));

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Stock information retrieved successfully',
            'data' => $stockData
        ]));

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

        $params = $request->getQueryParams();
        $page = isset($params['page']) && (int)$params['page'] > 0 ? (int)$params['page'] : 1;
        $perPage = isset($params['per_page']) && (int)$params['per_page'] > 0 ? (int)$params['per_page'] : 10;
        $offset = ($page - 1) * $perPage;

        $query = $this->repository->findBy('user_id', $userData['user_id'])
            ?->orderBy('date', 'DESC');

        $total = $query->count();
        $items = $query->skip($offset)->take($perPage)->get()->toArray();

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'History retrieved successfully',
            'data' => [
                'items' => $items,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

}
