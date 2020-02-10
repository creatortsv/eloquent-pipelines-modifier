<?php

namespace Creatortsv\EloquentPipelinesModifier\Conditions;

use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Condition
 * @package Creatortsv\EloquentPipelinesModifier\Conditions
 */
class Condition extends ConditionAbstract
{
    const CONDITION_IN = 'in';
    const CONDITION_NOT_IN = 'not_in';

    /**
     * @return array
     */
    public static function conditions(): array
    {
        return [
            self::CONDITION_IN,
            self::CONDITION_NOT_IN,
        ];
    }

    /**
     * @return mixed
     */
    public function parse(): Closure
    {
        return (function (Builder $builder): void {
            foreach ($this->value as $key => $value) {
                switch ($key) {
                    case self::CONDITION_IN:
                        break;
                    case self::CONDITION_NOT_IN:
                        break;
                    default:
                        if (is_array($value)) {
                            break;
                        }

                        $builder->where($key, $value);
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
        // TODO: Implement from() method.
    }
}
