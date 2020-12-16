<?php

namespace Creatortsv\EloquentPipelinesModifier;

use Creatortsv\EloquentPipelinesModifier\Modifiers\ModifierAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pipeline\Pipeline;
use InvalidArgumentException;

abstract class ModifierFactory
{
    /**
     * @param Builder|Relation $query
     * @param ModifierAbstract[] $modifiers
     * @param Builder
     */
    public static function modifyTo($query, array $modifiers = []): Builder
    {
        $query instanceof Relation && ($query = $query->getQuery());

        if (! ($query instanceof Builder)) {
            throw new InvalidArgumentException('Wrong parameter $query');
        }

        return app(Pipeline::class)
            ->send($query)
            ->through($modifiers ?: config('modifier.modifiers'))
            ->then(function ($builder) {
                return $builder;
            });
    }
}
