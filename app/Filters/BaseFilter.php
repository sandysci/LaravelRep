<?php


namespace App\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use ReflectionClass;

abstract class BaseFilter
{

    protected $request;
    protected $builder;

    public function __construct(Request $request) {
        $this->request = $request;
    }

      /**
     * Get all the available filter methods.
     *
     * @return array
     */
    protected function getFilterMethods()
    {
        $class  = new ReflectionClass(static::class);

        $methods = array_map(function($method) use ($class) {
            if ($method->class === $class->getName()) {
                return $method->name;
            }
            return null;
        }, $class->getMethods());

        return array_filter($methods);
    }

    public function apply(Builder $builder) {
        $this->builder = $builder;
        foreach ($this->filters () as $name => $value) {
            if(method_exists ($this, $name)) {
                call_user_func_array ([$this, $name], array_filter ([$value]));
            }
        }

        return $this->builder;
    }

    public function filters() {
        return $this->request->all();
    }
}
