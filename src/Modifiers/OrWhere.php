<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Creatortsv\EloquentPipelinesModifier\Conditions\Condition;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class OrWhere
 * @package Creatortsv\EloquentPipelinesModifier\Modifiers
 */
class OrWhere extends ModifierAbstract
{
    /**
     * @inheritDoc
     */
    protected function apply(Builder $builder): Builder
    {
        if (!empty($this->value)) {
            $builder->where(function (Builder $query) {
                foreach ($this->value as $condition) {
                    $query->orWhere((new Condition($condition))->parse());
                }
            });
        }

        return $builder;
    }

    /**
     * @inheritDoc
     */
    protected function extract(string $value)
    {
        if (($json = json_decode($value, true)) !== null && is_array($json)) {
            return $json;
        }

        return null;
    }
}
