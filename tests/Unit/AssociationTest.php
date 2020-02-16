<?php

namespace Tests\Unit;

use Closure;
use Creatortsv\EloquentPipelinesModifier\Conditions\Association;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

class AssociationTest extends TestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $app = new Application('../../');
        $app->bind('config', function () {
            return new class {
                public function get(string $key, $default = null)
                {
                    foreach (explode('.', $key) as $i => $string) {
                        if ($i === 0) {
                            $result = include __DIR__ . '/../../config/' . $string . '.php';
                        } else {
                            $result = ($result ?? [])[$string] ?? [];
                        }
                    }

                    return $result ?? $default;
                }
            };
        });
    }

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
