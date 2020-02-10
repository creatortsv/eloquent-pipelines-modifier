<?php

namespace Creatortsv\EloquentPipelinesModifier\Conditions;

/**
 * Class Association
 * @package Creatortsv\EloquentPipelinesModifier\Conditions
 */
class Association extends ConditionAbstract
{
    /**
     * @return string
     */
    public function parse(): string
    {
        return str_replace(config('modifier.delimiters.associations'), ' as ', $this->value);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public static function from($value): array
    {
        $value = is_array($value) ? $value : explode(config('modifier.delimiters.fields'), $value);
        return array_map(function (string $key) use ($value): string {
            return is_numeric($key)
                ? (new self($value[$key]))->parse()
                : $key . ' as ' . $value[$key];
        }, array_keys($value));
    }
}
