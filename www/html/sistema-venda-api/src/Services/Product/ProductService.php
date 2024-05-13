<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;

class ProductService implements ProductServiceInterface
{
    private $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    public function getAll(): array
    {
        return $this->productRepository->getAll();
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->getById($id);
    }

    public function getById(int $id): ?Product
    {
        return $this->productRepository->getById($id);
    }

    public function create(array $data): ?Product
    {
        return $this->productRepository->create($data);
    }

    public function update(int $id, array $data): ?Product
    {
        return $this->productRepository->update($id,$data);
    }

    public function delete(int $id): bool
    {
        return $this->productRepository->delete($id);
    }
}
