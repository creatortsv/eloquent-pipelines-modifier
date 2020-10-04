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
     * @param Builder|Relation $query
     * @param ModifierAbstract[] $modifiers
     * @return Builder
     */
    public static function modifyTo($query, array $modifiers = []): Builder
    {
        if ($query instanceof Relation) {
            if (get_class($query->getParent()) !== static::class) {
                throw new InvalidArgumentException('Relation query builder must be related of the Model class');
            }
        } else if ($query instanceof Builder) {
            if (get_class($query->getModel()) !== static::class) {
                throw new InvalidArgumentException('Query must be query of the Model class');
            }
        } else {
            throw new InvalidArgumentException('Query must be instance of the Relation or the Builder class');
        }

        return app(Pipeline::class)
            ->send($query)
            ->through($modifiers ?: config('modifier.modifiers'))
            ->then(function ($builder) {
                return $builder;
            });
    }
}
