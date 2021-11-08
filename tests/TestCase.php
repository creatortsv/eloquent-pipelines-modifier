<?php

namespace Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\PostgresConnection;
use Illuminate\Foundation\Application;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
    }

    protected function createApplication()
    {
        $app = new Application('../../');
        $app->bind('config', function () {
            return new class {
                public function get(string $key, $default = null)
                {
                    foreach (explode('.', $key) as $i => $string) {
                        if ($i === 0) {
                            $result = include __DIR__ . '/../config/' . $string . '.php';
                        } else {
                            $result = ($result ?? [])[$string] ?? [];
                        }
                    }

                    return $result ?? $default;
                }
            };
        });
    }
}
