<?php

namespace Tests\Unit;

use Creatortsv\EloquentPipelinesModifier\Modifiers\Limit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\PostgresConnection;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @covers \Creatortsv\EloquentPipelinesModifier\Modifiers\Limit
 */
class LimitTest extends TestCase
{
    /**
     * @covers \Creatortsv\EloquentPipelinesModifier\Modifiers\Limit::handle
     */
    public function testHandle(): void
    {
        $request = new Request();

        $modifier = new Limit($request);

        $builder = new Builder(
            $query = new \Illuminate\Database\Query\Builder(new PostgresConnection(function () {
                //
            }))
        );

        // 1. Non-reaction

        $before = $query->limit;

        $modifier->handle($request, function ($request) use ($builder) {
            return $builder;
        });

        self::assertEquals($before, $query->limit);

        // 2. Apply new limit

        $request->merge([
            '_limit' => $limit = 7,
        ]);

        $modifier = new Limit($request);

        $modifier->handle($request, function ($request) use ($builder) {
            return $builder;
        });

        self::assertEquals($limit, $query->limit);
    }
}
