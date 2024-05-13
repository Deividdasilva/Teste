<?php

namespace App\Services\ProductType;

use App\Models\ProductType;

interface ProductTypeServiceInterface
{
    /**
     * Get all productTypeTypes from the database.
     *
     * @return array|null The collection of productTypes or null if no productType are found.
     */
    public function getAll(): array;

    /**
     * Get one productType from the database.
     * @param int $id
     * @return ProductType|null one productType or null if no productType are found.
     */
    public function getById(int $id): ?ProductType;

    /**
     * Create one productType from the database.
     * @param array $data
     * @return ProductType|null one productType or null if no productType are found.
     */
    public function create(array $data): ?ProductType;

    /**
     * Update one productType from the database.
     * @param int $id
     * @param array $data
     * @return ProductType|null one productType or null if no productType are found.
     */
    public function update(int $id, array $data): ?ProductType;

    /**
     * Update one productType from the database.
     * @param int $id
     * @return bool true|false
     */
    public function delete(int $id): bool;
}
