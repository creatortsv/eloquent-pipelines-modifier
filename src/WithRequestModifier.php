<?php

namespace Creatortsv\EloquentPipelinesModifier;

use Creatortsv\EloquentPipelinesModifier\Modifiers\Modifier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

/**
 * Trait WithRequestModifier
 * @package Creatortsv\EloquentPipelinesModifier
 */
trait WithRequestModifier
{
    /**
     * @param Modifier[] $modifiers
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
