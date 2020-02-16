<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Creatortsv\EloquentPipelinesModifier\Conditions\Condition;
use PHPUnit\Framework\TestCase;

class ConditionTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function whichMethod(): void
    {
        foreach ([
            Condition::CONDITION_EQUAL,
            Condition::CONDITION_NOT_EQUAL,
            Condition::CONDITION_LESS_EQUAL,
            Condition::CONDITION_LESS,
            Condition::CONDITION_GREATER_EQUAL,
            Condition::CONDITION_GREATER,
        ] as $string) {
            $this->assertEquals('where', Condition::whichMethod($string, 'value'));
            $this->assertEquals('whereDate', Condition::whichMethod($string, Carbon::now()));
            $this->assertEquals('whereDate', Condition::whichMethod($string, Carbon::now()->format('Y-m-d')));
            $this->assertEquals('whereDate', Condition::whichMethod($string, Carbon::now()->format('d.m.Y')));
            $this->assertEquals('whereDate', Condition::whichMethod($string, '1st may 2020'));
        }

        foreach ([
            Condition::CONDITION_LIKE,
            Condition::CONDITION_LIKE_RIGHT,
            Condition::CONDITION_LIKE_LEFT,
        ] as $string) {
            $this->assertEquals('where', Condition::whichMethod($string, 'value'));
        }

        $this->assertEquals('whereIn', Condition::whichMethod(Condition::CONDITION_IN, 'value'));
        $this->assertEquals('whereNotIn', Condition::whichMethod(Condition::CONDITION_NOT_IN, 'value'));
        $this->assertEquals('whereBetween', Condition::whichMethod(Condition::CONDITION_BETWEEN, 'value'));
        $this->assertEquals('whereNotBetween', Condition::whichMethod(Condition::CONDITION_NOT_BETWEEN, 'value'));
    }

    /**
     * @test
     * @return void
     */
    public function whichArgs(): void
    {
        foreach (Condition::conditions() as $string) {
            $args = Condition::whichArgs($string, 'value');
            if (in_array($string, [
                Condition::CONDITION_IN,
                Condition::CONDITION_NOT_IN,
                Condition::CONDITION_NOT_BETWEEN,
                Condition::CONDITION_BETWEEN,
            ])) {
                $this->assertCount(1, $args);
            } else {
                $this->assertCount(2, $args);
            }

            switch ($string) {
                case Condition::CONDITION_IN:
                case Condition::CONDITION_NOT_IN:
                case Condition::CONDITION_NOT_BETWEEN:
                case Condition::CONDITION_BETWEEN:
                    $this->assertEquals([['value']], $args);
                    break;
                case Condition::CONDITION_EQUAL:
                    $this->assertEquals(['=', 'value'], $args);
                    break;
                case Condition::CONDITION_NOT_EQUAL:
                    $this->assertEquals(['!=', 'value'], $args);
                    break;
                case Condition::CONDITION_LESS_EQUAL:
                    $this->assertEquals(['<=', 'value'], $args);
                    break;
                case Condition::CONDITION_LESS:
                    $this->assertEquals(['<', 'value'], $args);
                    break;
                case Condition::CONDITION_GREATER_EQUAL:
                    $this->assertEquals(['>=', 'value'], $args);
                    break;
                case Condition::CONDITION_GREATER:
                    $this->assertEquals(['>', 'value'], $args);
                    break;
                case Condition::CONDITION_LIKE:
                    $this->assertEquals(['like', '%value%'], $args);
                    break;
                case Condition::CONDITION_LIKE_LEFT:
                    $this->assertEquals(['like', 'value%'], $args);
                    break;
                case Condition::CONDITION_LIKE_RIGHT:
                    $this->assertEquals(['like', '%value'], $args);
                    break;
            }
        }
    }
}
