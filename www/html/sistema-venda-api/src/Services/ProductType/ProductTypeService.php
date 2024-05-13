<?php

namespace App\Services\ProductType;

use App\Models\ProductType;
use App\Repositories\ProductType\ProductTypeRepository;

class ProductTypeService implements ProductTypeServiceInterface
{
    private $productTypeRepository;

    public function __construct()
    {
        $this->productTypeRepository = new ProductTypeRepository();
    }

    public function getAll(): array
    {
        return $this->productTypeRepository->getAll();
    }

    public function getById(int $id): ?ProductType
    {
        return $this->productTypeRepository->getById($id);
    }

    public function create(array $data): ?ProductType
    {
        return $this->productTypeRepository->create($data);
    }

    public function update(int $id, array $data): ?ProductType
    {
        return $this->productTypeRepository->update($id,$data);
    }

    public function delete(int $id): bool
    {
        return $this->productTypeRepository->delete($id);
    }
}
