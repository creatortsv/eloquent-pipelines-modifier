<?php

namespace Creatortsv\EloquentPipelinesModifier\Conditions;

/**
 * Interface ConditionInterface
 * @package Creatortsv\EloquentPipelinesModifier\Conditions
 */
abstract class ConditionAbstract
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * ConditionAbstract constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    abstract public function parse();

    /**
     * @param mixed $value
     * @return mixed
     */
    abstract public static function from($value): array;
}
