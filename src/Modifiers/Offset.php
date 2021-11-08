<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Illuminate\Database\Eloquent\Builder;

class Offset extends ModifierAbstract
{
    protected function apply(Builder $builder): Builder
    {
        return $builder->offset($this->value);
    }

    protected function extract(string $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }
}
