<?php


namespace Xwpd\ThinkTesting\Traits;

use think\Container;
use think\Facade;
use Mockery;
use Closure;

trait InteractsWithContainer
{
    /**
     * Register an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  object  $instance
     * @return object
     */
    protected function swap($abstract, $instance)
    {
        return $this->instance($abstract, $instance);
    }

    /**
     * Register an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  object  $instance
     * @return object
     */
    protected function instance($abstract, $instance)
    {
        Container::getInstance()->bindTo($abstract,$instance);

        return $instance;
    }

    /**
     * Mock an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  Closure|null  $mock
     * @return object
     */
    protected function mock($abstract, Closure $mock = null)
    {
        return $this->instance($abstract, Mockery::mock(...array_filter(func_get_args())));
    }

    /**
     * Spy an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  Closure|null  $mock
     * @return object
     */
    protected function spy($abstract, Closure $mock = null)
    {
        return $this->instance($abstract, Mockery::spy(...array_filter(func_get_args())));
    }
}