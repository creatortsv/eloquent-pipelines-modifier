<?php

namespace Creatortsv\EloquentPipelinesModifier;

use Creatortsv\EloquentPipelinesModifier\Modifiers\ModifierAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pipeline\Pipeline;
use InvalidArgumentException;

/**
 * Trait WithModifier
 * @package Creatortsv\EloquentPipelinesModifier
 */
trait WithModifier
{
    /**
     * @param ModifierAbstract[] $modifiers
     * @return Builder
     */
    public static function modify(array $modifiers = []): Builder
    {
        return static::modifyTo(static::query(), $modifiers);
    }

    /**
     * @param ModifierAbstract[] $modifiers
     * @return Builder
     */
    public static function modifyTo(Builder $query, array $modifiers = []): Builder
    {
        if ($query instanceof Relation && get_class($query->getParent()) !== static::class) {
            throw new InvalidArgumentException('Relation query builder must be related of the Model class');
        }

        if (get_class($query->getModel()) !== static::class) {
            throw new InvalidArgumentException('Query builder must be query of the Model class');
        }
        
        return app(Pipeline::class)
            ->send($query)
            ->through($modifiers ?: config('modifier.modifiers'))
            ->then(function ($builder) {
                return $builder;
            });
    }
}
