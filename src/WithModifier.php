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
        return ModifierFactory::modifyTo(self::query(), $modifiers);
    }
}
