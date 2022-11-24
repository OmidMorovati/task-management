<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use PHPUnit\Framework\MockObject\UnknownClassException;

abstract class EloquentBaseRepository implements RepositoryInterface
{
    protected ?Model $model;

    abstract protected function model(): string;

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function __construct()
    {
        $this->makeModel();
    }

    private function makeModel(): void
    {
        $model = App::make($this->model());

        if (!$model instanceof Model) {
            throw new UnknownClassException($this->model());
        }
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->query()->with($relations)->get($columns);
    }

    public function find(int $id, $columns = ['*'], array $relations = []): ?Model
    {
        $this->model = $this->model->query()->with($relations)->find($id, $columns);
        return $this->model;
    }

    public function store(array $item): Model
    {
        return $this->model->query()->create($item);
    }

    public function update(array $item, int $id = null): Model
    {
        $model = $this->find($id);
        if (isset($model)) {
            $model->update($item);
            return $model;
        }
        throw new ModelNotFoundException(Lang::get('response.model_not_found'));
    }

    public function delete(int $id): int
    {
        $model = $this->find($id);
        if (isset($model)) {
            return $this->model::destroy($id);
        }
        throw new ModelNotFoundException(Lang::get('response.model_not_found'));
    }

    public function bulkDelete(array $ids)
    {
        $query = $this->model->query()->whereIn('id', $ids);
        if ($query->exists()) {
            return $query->delete();
        }
        throw new ModelNotFoundException(Lang::get('response.model_not_found'));
    }

    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollBack(int $toLevel = null): void
    {
        DB::rollBack($toLevel);
    }
}
