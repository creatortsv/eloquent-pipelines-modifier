<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Creatortsv\EloquentPipelinesModifier\Conditions\Association;
use Creatortsv\EloquentPipelinesModifier\Conditions\Condition;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CountModifier
 * @package Creatortsv\EloquentPipelinesModifier\Modifiers
 */
class Count extends Modifier
{
    /**
     * - 'one,two:alias_two'
     * - ['one','two:alias_two']
     * - {'one': 'alias_one', 'two': condition, 'three:alias_three': condition}
     *
     * @param Builder $builder
     * @return Builder
     */
    protected function apply(Builder $builder): Builder
    {
        if (is_string($this->value)) {
        /** Simple string */
            return $builder->withCount(Association::from($this->value));
        }

        if (is_array($this->value)) {
            $keys = [];
        /** First make simple property:value json elements  */
            $counts = Association::from(array_filter($this->value, (function ($value, string $key) use (&$keys): bool {
                return is_string($value) || !($key && ($keys[] = $key));
            })->bindTo($this), ARRAY_FILTER_USE_BOTH));

            foreach ($keys as $key) {
                $full = ($association = new Association($key))->parse();
                $counts[$full] = (new Condition($this->value[$key], $association->name))->parse();
            }

            return $builder->withCount($counts);
        }

        return $builder;
    }

    /**
     * @param string $value
     * @return array|bool|false|int|mixed|string
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
