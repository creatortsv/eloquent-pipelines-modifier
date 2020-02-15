<?php

use Creatortsv\EloquentPipelinesModifier\Modifiers\Count;
use Creatortsv\EloquentPipelinesModifier\Modifiers\Filter;
use Creatortsv\EloquentPipelinesModifier\Modifiers\Select;
use Creatortsv\EloquentPipelinesModifier\Modifiers\Sort;
use Creatortsv\EloquentPipelinesModifier\Modifiers\With;

return [
    'modifiers' => [
        Count::class,
        Select::class,
        Filter::class,
        Sort::class,
        With::class,
    ],
    'delimiters' => [
        'associations' => ':',
        'fields' => ',',
    ],
];
