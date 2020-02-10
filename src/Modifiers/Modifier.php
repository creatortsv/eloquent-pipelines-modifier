<?php

namespace Creatortsv\EloquentPipelinesModifier\Modifiers;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class Modifier
 * @package Creatortsv\EloquentPipelinesModifier\Modifiers
 */
abstract class Modifier
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|string
     */
    protected $value;

    /**
     * @var array
     */
    protected $params;

    /**
     * Modifier constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        if (!is_string($this->name = array_search(get_class($this), config('modifier.modifiers')))) {
            $this->name = '_' . Str::snake(class_basename($this));
        }

        if (($value = $request->input($this->name)) !== null) {
            $this->value = $this->extract($value);
        }

        $this->params = $request->except($this->name);
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return Builder
     */
    public function handle($request, Closure $next): Builder
    {

        if ($this->value !== null) {
            return $this->apply($next($request));
        }

        return $next($request);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected abstract function apply(Builder $builder): Builder;

    /**
     * @param string $value
     * @return mixed
     */
    protected abstract function extract(string $value);
}
