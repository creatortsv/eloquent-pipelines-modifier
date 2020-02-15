<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Creatortsv\EloquentPipelinesModifier\Conditions\Condition;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Filter
 * @package Creatortsv\EloquentPipelinesModifier\Modifiers
 */
class Filter extends ModifierAbstract
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function apply(Builder $builder): Builder
    {
        if (is_array($this->value)) {
            $builder->where((new Condition($this->value))->parse());
        }

        return $builder;
    }

    /**
     * @param string $value
     * @return array|null
     */
    protected function extract(string $value)
    {
        if (($json = json_decode($value, true)) !== null) {
            return $json;
        }

        return null;
    }
}
