<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface RepositoryContract
{
    public function find(string|int $id): Model|null;

    /**
     * @throws ModelNotFoundException
     */
    public function findOrFail(string|int $id): Model;

    public function get(): Collection|array;

    public function getPaginated(int $perPage = 10): LengthAwarePaginator;

    public function create(array $attributes): Model;

    public function update(Model $model, array $values): bool;

    /**
     * Delete the model from the database.
     *
     * @param Model $model
     * @return bool
     *
     * @throws \LogicException
     */
    public function delete(Model $model): bool;

    public function query(): Builder;
}
