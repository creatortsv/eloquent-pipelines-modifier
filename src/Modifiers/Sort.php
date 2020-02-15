<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Order
 * @package Creatortsv\EloquentPipelinesModifier\Modifiers
 */
class Sort extends ModifierAbstract
{

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function apply(Builder $builder): Builder
    {
        if (!is_array($this->value)) {
            $this->value = explode(config('modifier.delimiters.fields'), $this->value);
        }

        foreach ($this->value as $key => $value) {
            if (is_string($key)) {
                $name = $key;
                $dir = $value;
            } else {
                $name = $value;
                $dir = 'asc';
                if (substr($value, 0, 1) === '-') {
                    $name = substr($value, 1);
                    $dir = 'desc';
                }
            }

            $builder->orderBy($name, $dir);
        }

        return $builder;
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

        if ((bool)preg_match('/^\-?[a-z_.,]+$/', $value)) {
            return $value;
        }

        return null;
    }
}