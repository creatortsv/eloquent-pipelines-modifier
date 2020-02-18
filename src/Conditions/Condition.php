<?php

namespace Creatortsv\EloquentPipelinesModifier\Conditions;

use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Class Condition
 * @package Creatortsv\EloquentPipelinesModifier\Conditions
 */
class Condition extends ConditionAbstract
{
    const DELIMITER_RELATIONS = '.';

    const CONDITION_EQUAL = 'equal';
    const CONDITION_NOT_EQUAL = 'not_equal';
    const CONDITION_IN = 'in';
    const CONDITION_NOT_IN = 'not_in';
    const CONDITION_LIKE = 'like';
    const CONDITION_LIKE_LEFT = 'like_left';
    const CONDITION_LIKE_RIGHT = 'like_right';
    const CONDITION_BETWEEN = 'between';
    const CONDITION_NOT_BETWEEN = 'not_between';
    const CONDITION_GREATER = 'greater';
    const CONDITION_LESS = 'less';
    const CONDITION_GREATER_EQUAL = 'greater_equal';
    const CONDITION_LESS_EQUAL = 'less_equal';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $relation;

    /**
     * Condition constructor.
     * @param $value
     * @param string|null $name
     */
    public function __construct($value, string $name = null)
    {
        $this->name = $name;
        parent::__construct($value);
    }

    /**
     * @return array
     */
    public static function conditions(): array
    {
        return [
            self::CONDITION_EQUAL,
            self::CONDITION_NOT_EQUAL,
            self::CONDITION_IN,
            self::CONDITION_NOT_IN,
            self::CONDITION_LIKE,
            self::CONDITION_LIKE_LEFT,
            self::CONDITION_LIKE_RIGHT,
            self::CONDITION_BETWEEN,
            self::CONDITION_NOT_BETWEEN,
            self::CONDITION_GREATER,
            self::CONDITION_GREATER_EQUAL,
            self::CONDITION_LESS,
            self::CONDITION_LESS_EQUAL,
        ];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return string
     */
    public static function whichMethod(string $key, $value): string
    {
        $method = 'where';

        try {
            $isDate = ($value instanceof Carbon || is_string($value)) && Carbon::parse($value);
        } catch (Exception $e) {
            $isDate = false;
        }

        if ($isDate && in_array($key, [
            self::CONDITION_EQUAL,
            self::CONDITION_NOT_EQUAL,
            self::CONDITION_GREATER,
            self::CONDITION_GREATER_EQUAL,
            self::CONDITION_LESS,
            self::CONDITION_LESS_EQUAL,
        ])) {
            $method .= 'Date';
        }

        if (in_array($key, [
            self::CONDITION_IN,
            self::CONDITION_NOT_IN,
            self::CONDITION_BETWEEN,
            self::CONDITION_NOT_BETWEEN,
        ])) {
            $method .= Str::ucfirst(Str::camel($key));
        }

        return $method;
    }

    /**
     * @param string $key
     * @param $value
     * @return array
     */
    public static function whichArgs(string $key, $value): array
    {
        $args = [];
        switch ($key) {
            case self::CONDITION_LIKE:
            case self::CONDITION_LIKE_LEFT:
            case self::CONDITION_LIKE_RIGHT:
                $args[] = 'like';
                break;
            case self::CONDITION_EQUAL:
                $args[] = '=';
                break;
            case self::CONDITION_NOT_EQUAL:
                $args[] = '!=';
                break;
            case self::CONDITION_GREATER:
                $args[] = '>';
                break;
            case self::CONDITION_GREATER_EQUAL:
                $args[] = '>=';
                break;
            case self::CONDITION_LESS:
                $args[] = '<';
                break;
            case self::CONDITION_LESS_EQUAL:
                $args[] = '<=';
                break;
        }

        if (in_array($key, [
            self::CONDITION_IN,
            self::CONDITION_NOT_IN,
            self::CONDITION_BETWEEN,
            self::CONDITION_NOT_BETWEEN,
        ])) {
            $args[] = (array)$value;
        } elseif ($key === self::CONDITION_LIKE) {
            $args[] = "%$value%";
        } elseif ($key === self::CONDITION_LIKE_LEFT) {
            $args[] = "$value%";
        } elseif ($key === self::CONDITION_LIKE_RIGHT) {
            $args[] = "%$value";
        } else {
            $args[] = $value;
        }

        return $args;
    }

    /**
     * @param Builder $builder
     * @param string|null $path
     * @return string|null
     */
    public static function getRelationsPath($builder, string $path = null)
    {
        if ((bool)$path) {
            $model = $builder->getModel();
            foreach (explode(self::DELIMITER_RELATIONS, $path) as $name) {
                if (method_exists($model, $name)) {
                    $chain = $chain ?? '' . (isset($chain) ? ".$name" : $name);
                    $model = $model
                        ->$name()
                        ->getModel();

                    continue;
                }

                break;
            }
        }

        return $chain ?? null;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public static function from($value): array
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function parse(): Closure
    {
        /**
         * For filter {
         *      posts.comments: 1,
         *      posts.comments: {
         *          greater: 1,
         *          user.id: 1,
         *          user_id: 1,
         *      },
         * }
         */
        return (function ($builder): void {
            $relation = self::getRelationsPath($builder, $this->name);
            $column = str_replace($relation, '', (string)$this->name) ?: null;
            $values = $this->value;

            if (!is_array($values)) {
                $values = [self::CONDITION_EQUAL => $values];
            }

            if ($column !== null) {
                $column = substr($column, 1);
            }

            if ($relation !== null) {
                if ($column === null) {
                    foreach ($values as $key => $value) {
                        if (in_array($key, [
                            self::CONDITION_EQUAL,
                            self::CONDITION_LESS,
                            self::CONDITION_LESS_EQUAL,
                            self::CONDITION_GREATER,
                            self::CONDITION_GREATER_EQUAL,
                        ])) {
                            $builder->has($relation, ...self::whichArgs($key, $value));
                            unset($values[$key]);
                        }
                    }
                }

                $values && $builder->whereHas($relation, (new self($values, $column))->parse());
            } else {
                foreach ($values as $key => $value) {
                    if (in_array($key, self::conditions())) {
                        $builder->{self::whichMethod($key, $value)}($this->name, ...self::whichArgs($key, $value));
                        continue;
                    }

                    $builder->where((new self($value, $key))->parse());
                }
            }
        })->bindTo($this);
    }
}
