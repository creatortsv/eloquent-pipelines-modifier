<?php

namespace Tests\Unit;

use Creatortsv\EloquentPipelinesModifier\Conditions\Association;
use Tests\TestCase;

class AssociationTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function parse(): void
    {
        $assoc = (new Association('first:one'))->parse();
        $this->assertEquals('first as one', $assoc);
    }

    /**
     * @test
     * @return void
     */
    public function associationsFrom(): void
    {
        $data = Association::from('first:one,second:two');
        $this->assertEquals([
            'first as one',
            'second as two',
        ], $data);

        $data = Association::from([
            'first:one',
            'second' => 'two',
            'three',
        ]);

        $this->assertEquals([
            'first as one',
            'second as two',
            'three',
        ], $data);
    }
}
