<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Creatortsv\EloquentPipelinesModifier\Conditions\Association;
use Illuminate\Database\Eloquent\Builder;

class Select extends Modifier
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function apply(Builder $builder): Builder
    {
        return $builder->select(Association::from($this->value));
    }

    /**
     * @param string $value
     * @return mixed
     */
    protected function extract(string $value)
    {
        if (($json = json_decode($value, true)) !== null) {
            return $json;
        }

        if ((bool)preg_match('/^[a-z][a-z_.,:]+[a-z]$/', $value)) {
            return $value;
        }

        return null;
    }
}
