<?php

namespace App\Domain\Stock\Repository;


use App\Domain\Stock\StockHistory;

class StockHistoryRepository
{
    public function __construct()
    {
    }

    public function findBy(string $property, string|int $valueProperty)
    {
        return StockHistory::where([$property => $valueProperty]);

    }

    public function create(array $properties): ?StockHistory
    {
        return StockHistory::create($properties);
    }
}
