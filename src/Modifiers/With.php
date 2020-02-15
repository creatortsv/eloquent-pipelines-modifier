<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Creatortsv\EloquentPipelinesModifier\Conditions\Condition;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class With
 * @package Creatortsv\EloquentPipelinesModifier\Modifiers
 */
class With extends ModifierAbstract
{

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function apply(Builder $builder): Builder
    {
        if (is_string($this->value)) {
            return $builder->with($this->value);
        }

        $data = [];
        foreach ($this->value as $key => $value) {
            $data[$key] = is_string($key) ? (new Condition($value))->parse() : $value;
        }

        return $builder->with($data);
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
