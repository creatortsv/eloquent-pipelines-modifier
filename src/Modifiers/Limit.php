<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Illuminate\Database\Eloquent\Builder;

class Limit extends ModifierAbstract
{
    protected function apply(Builder $builder): Builder
    {
        return $builder->limit($this->value);
    }

    protected function extract(string $value)
    {
        return is_numeric($value) ? (int) $value : null;
    }
}
