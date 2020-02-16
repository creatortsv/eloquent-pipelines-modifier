<?php

namespace Creatortsv\EloquentPipelinesModifier\Conditions;

use Illuminate\Support\Facades\Config;

/**
 * Class Association
 * @package Creatortsv\EloquentPipelinesModifier\Conditions
 */
class Association extends ConditionAbstract
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $alias;

    /**
     * @return string
     */
    public function parse(): string
    {
        $result = str_replace(Config::get('modifier.delimiters.associations'), ' as ', $this->value);
        $sections = explode(' as ', $result);
        $this->name = $sections[0];
        $this->alias = $sections[1] ?? null;

        return $result;
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
