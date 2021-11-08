<?php

namespace Creatortsv\EloquentPipelinesModifier;

use Creatortsv\EloquentPipelinesModifier\Modifiers\ModifierAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait WithModifier
 * @package Creatortsv\EloquentPipelinesModifier
 *
 * @mixin Model
 */
trait WithModifier
{
    public static function modify(ModifierAbstract ...$modifiers): Builder
    {
        return ModifierFactory::modifyTo(self::query(), ...$modifiers);
    }
}
