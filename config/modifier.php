<?php

use Creatortsv\EloquentPipelinesModifier\Modifiers\Count;
use Creatortsv\EloquentPipelinesModifier\Modifiers\Select;

return [
    'modifiers' => [
        'my_count' => Count::class,
        Select::class,
    ],
    'delimiters' => [
        'associations' => ':',
        'fields' => ',',
    ],
];
