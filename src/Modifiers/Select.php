<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Creatortsv\EloquentPipelinesModifier\Conditions\Association;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Select
 * @package Creatortsv\EloquentPipelinesModifier\Modifiers
 */
class Select extends ModifierAbstract
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
     * @return array|string|null
     */
    protected function extract(string $value)
    {
        if (($json = json_decode($value, true)) !== null) {
            return $json;
        }

        if (preg_match('/^[a-z][a-z_.,:]+[a-z]$/', $value)) {
            return $value;
        }

        return null;
    }
}
