<?php

namespace Creatortsv\EloquentPipelinesModifier\Conditions;

/**
 * Class Association
 * @package Creatortsv\EloquentPipelinesModifier\Conditions
 */
class Association extends ConditionAbstract
{
    public string $name;
    public string $alias;

    public function parse(): string
    {
        $result = str_replace(config('modifier.delimiters.associations'), ' as ', $this->value);
        $sections = explode(' as ', $result);
        $this->name = $sections[0];
        $this->alias = $sections[1] ?? null;

        return $result;
    }

    /**
     * @param array|string $value
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
