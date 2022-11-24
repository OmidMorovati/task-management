<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Facades\DB;

interface RepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []);

    public function find(int $id, $columns = ['*'], array $relations = []);

    public function store(array $item);

    public function update(array $item, int $id = null);

    public function delete(int $id);

    public function bulkDelete(array $ids);

    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(int $toLevel = null): void;
}
