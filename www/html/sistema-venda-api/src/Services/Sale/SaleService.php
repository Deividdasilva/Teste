<?php

namespace App\Services\Sale;

use App\Models\Sale;
use App\Repositories\Sale\SaleRepository;

class SaleService implements SaleServiceInterface
{
    private $saleRepository;

    public function __construct()
    {
        $this->saleRepository = new SaleRepository();
    }

    public function getAll(): array
    {
        return $this->saleRepository->getAll();
    }

    public function getById(int $id): ?Sale
    {
        return $this->saleRepository->getById($id);
    }

    public function create(array $data): bool
    {
        return $this->saleRepository->create($data);
    }

    public function update(int $id, array $data): ?Sale
    {
        return $this->saleRepository->update($id,$data);
    }

    public function delete(int $id): bool
    {
        return $this->saleRepository->delete($id);
    }
}
