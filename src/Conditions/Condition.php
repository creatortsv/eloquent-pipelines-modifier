<?php

namespace Creatortsv\EloquentPipelinesModifier\Conditions;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Condition
 * @package Creatortsv\EloquentPipelinesModifier\Conditions
 */
class Condition extends ConditionAbstract
{
    const CONDITION_EQUAL = 'equal';
    const CONDITION_IN = 'in';
    const CONDITION_NOT_IN = 'not_in';
    const CONDITION_LIKE = 'like';
    const CONDITION_LIKE_LEFT = 'like_left';
    const CONDITION_LIKE_RIGHT = 'like_right';
    const CONDITION_BETWEEN = 'between';
    const CONDITION_GREATER = 'greater';
    const CONDITION_LESS = 'less';
    const CONDITION_GREATER_EQUAL = 'greater_equal';
    const CONDITION_LESS_EQUAL = 'less_equal';

    /**
     * @var string
     */
    protected $name;

    /**
     * @return array
     */
    public static function conditions(): array
    {
        return [
            self::CONDITION_EQUAL,
            self::CONDITION_IN,
            self::CONDITION_NOT_IN,
            self::CONDITION_LIKE,
            self::CONDITION_LIKE_LEFT,
            self::CONDITION_LIKE_RIGHT,
            self::CONDITION_BETWEEN,
            self::CONDITION_GREATER,
            self::CONDITION_GREATER_EQUAL,
            self::CONDITION_LESS,
            self::CONDITION_LESS_EQUAL,
        ];
    }

    /**
     * Condition constructor.
     * @param $value
     * @param string $name
     */
    public function __construct($value, string $name)
    {
        $this->name = $name;
        parent::__construct($value);
    }

    /**
     * @return mixed
     */
    public function parse(): Closure
    {
        /** value = {"user_id": { "in":[2] }} */
        return (function (Builder $builder): void {
            foreach ($this->value as $key => $value) {
                switch ($key) {
                    case self::CONDITION_IN:
                        $builder->whereIn($this->name, (array)$value);
                        break;
                    case self::CONDITION_NOT_IN:
                        $builder->whereNotIn($this->name, (array)$value);
                        break;
                    default:
                        if (is_array($value)) {
                            $builder->where((new self($value, $key))->parse());
                            break;
                        }

                        if (($date = Carbon::parse($value))->isValid()) {
                            $builder->whereDate($key, $date);
                        } else {
                            $builder->where($key, $value);
                        }
                }
            }
        })->bindTo($this);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public static function from($value): array
    {
        return [];
    }
}
