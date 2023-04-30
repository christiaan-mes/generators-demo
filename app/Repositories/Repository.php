<?php

namespace App\Repositories;

use App\Contracts\RepositoryContract;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryContract
{
    public function __construct(private Model $model)
    {
        //
    }

    public function find(int|string $id): Model|null
    {
        return $this->query()->find(id: $id);
    }

    public function findWhere(Closure|array|string $where): Model|null
    {
        return $this->query()->where($where)->first();
    }

    public function findOrFail(string|int $id): Model
    {
        return $this->query()->findOrFail(id: $id);
    }

    public function get(): Collection|array
    {
        return $this->query()->get();
    }

    public function getWhere(Closure|array|string $where): Collection|array
    {
        return $this->query()->where($where)->get();
    }

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->query()->paginate(perPage: $perPage);
    }

    public function create(array $attributes): Model
    {
        return $this->query()->create(attributes: $attributes);
    }

    public function update(Model $model, array $values): bool
    {
        return $this->model->update(attributes: $values);
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }
}
