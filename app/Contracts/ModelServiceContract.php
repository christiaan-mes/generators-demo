<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ModelServiceContract
{
    public function create(array $attributes): Model;

    public function delete(string|int|Model $model): bool;
}
