<?php

namespace Creatortsv\EloquentPipelinesModifier;

use Creatortsv\EloquentPipelinesModifier\Modifiers\ModifierAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

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
        return app(Pipeline::class)
            ->send(self::query())
            ->through($modifiers ?: config('modifier.modifiers'))
            ->thenReturn();
    }
}
