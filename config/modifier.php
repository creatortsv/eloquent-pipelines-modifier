<?php

use Creatortsv\EloquentPipelinesModifier\Modifiers\Count;
use Creatortsv\EloquentPipelinesModifier\Modifiers\Filter;
use Creatortsv\EloquentPipelinesModifier\Modifiers\Select;
use Creatortsv\EloquentPipelinesModifier\Modifiers\Sort;
use Creatortsv\EloquentPipelinesModifier\Modifiers\With;
use Creatortsv\EloquentPipelinesModifier\Modifiers\OrWhere;

return [
    'modifiers' => [
        Count::class,
        Select::class,
        Filter::class,
        Sort::class,
        With::class,
        OrWhere::class,
    ],
    'delimiters' => [
        'associations' => ':',
        'fields' => ',',
    ],
];
