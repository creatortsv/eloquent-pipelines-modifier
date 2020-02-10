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
     * @var string
     */
    protected $name;

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
        return (function (Builder &$builder): void {
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
                            (new self($value, $key))->parse()($builder);
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
