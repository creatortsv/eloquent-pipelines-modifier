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
    public abstract function parse();

    /**
     * @param mixed $value
     * @return mixed
     */
    public static abstract function from($value): array;
}
