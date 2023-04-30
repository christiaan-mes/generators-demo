<?php

namespace App\Services;

use App\Contracts\ModelServiceContract;
use App\Contracts\RepositoryContract;
use Illuminate\Database\Eloquent\Model;

abstract class ModelService implements ModelServiceContract
{
    public function __construct(private readonly RepositoryContract $repository)
    {
        //
    }

    public function create(array $attributes): Model
    {
        return $this->repository->create(attributes: $attributes);
    }

    public function update(string|int|Model $model, array $values): bool
    {
        if ($model instanceof Model) {
            return $this->repository->update(model: $model, values: $values);
        }

        $model = $this->repository->findOrFail(id: $model);

        return $this->repository->update($model, values: $values);
    }

    public function delete(string|int|Model $model): bool
    {
        if ($model instanceof Model) {
            return $this->repository->delete(model: $model);
        }

        $model = $this->repository->findOrFail(id: $model);

        return $model->delete();
    }
}
