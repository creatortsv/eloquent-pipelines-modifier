<?php

namespace Tests\Unit;

use Creatortsv\EloquentPipelinesModifier\Modifiers\Offset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\PostgresConnection;
use Illuminate\Http\Request;
use Tests\TestCase;

class OffsetTest extends TestCase
{
    /**
     * @covers \Creatortsv\EloquentPipelinesModifier\Modifiers\Offset::handle
     */
    public function testHandle(): void
    {
        $request = new Request();

        $modifier = new Offset($request);

        $builder = new Builder(
            $query = new \Illuminate\Database\Query\Builder(new PostgresConnection(function () {
                //
            }))
        );

        // 1. Non-reaction

        $before = $query->offset;

        $modifier->handle($request, function ($request) use ($builder) {
            return $builder;
        });

        self::assertEquals($before, $query->offset);

        // 2. Apply new offset

        $request->merge([
            '_offset' => $offset = 7,
        ]);

        $modifier = new Offset($request);

        $modifier->handle($request, function ($request) use ($builder) {
            return $builder;
        });

        self::assertEquals($offset, $query->offset);
    }
}
