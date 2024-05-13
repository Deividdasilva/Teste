<?php

namespace App\Services\Sale;

use App\Models\Sale;

interface SaleServiceInterface
{
    /**
     * Get all sales from the database.
     *
     * @return array|null The collection of sales or null if no sale are found.
     */
    public function getAll(): array;

    /**
     * Get one sale from the database.
     * @param int $id
     * @return Sale|null one sale or null if no sale are found.
     */
    public function getById(int $id): ?Sale;

    /**
     * Create one sale from the database.
     * @param array $data
     * @return bool true|false.
     */
    public function create(array $data): bool;

     /**
     * Update one sale by id from the database.
     * @param int $id
     * @param array $data
     * @return Sale|null one sale or null if no sale are found.
     */
    public function update(int $id, array $data): ?Sale;

    /**
     * delete one sale logical by id from the database.
     * @param int $id
     * @return bool true|false.
     */
    public function delete(int $id): bool;
}
