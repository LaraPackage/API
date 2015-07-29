<?php

namespace spec\LaraPackage\Api\Config;

use Closure;
use Illuminate\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\LaraPackage\Api\Config\AppStub as App;

class ApiSpec extends ObjectBehavior
{
    function it_gets_an_item(App $app, Config\Repository $config)
    {
        $index = 'foo';
        $return = 'bar';

        $this->appExpectations($app, $config);
        $config->get('api.'.$index)->shouldBeCalledTimes(1)->willReturn($return);

        $this->getIndex($index)->shouldReturn($return);
    }

    function it_gets_an_item_for_an_api_version(App $app, Config\Repository $config)
    {
        $item = 'foo';
        $version = 4;
        $return = 'bar';

        $this->appExpectations($app, $config);
        $config->get('api.versions.'.$version.'.'.$item)->shouldBeCalledTimes(1)->willReturn($return);

        $this->getIndexForVersion($item, $version)->shouldReturn($return);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Config\Api');
    }

    function let(App $app)
    {
        $this->beConstructedWith($app);
    }

    /**
     * @param AppStub           $app
     * @param Config\Repository $config
     */
    protected function appExpectations(App $app, Config\Repository $config)
    {
        $app->offsetGet('config')->shouldBeCalled()->willReturn($config);
    }
}


class AppStub implements \Illuminate\Contracts\Container\Container, \ArrayAccess
{

    /**
     * Register a new after resolving callback.
     *
     * @param  string   $abstract
     * @param  \Closure $callback
     *
     * @return void
     */
    public function afterResolving($abstract, Closure $callback = null)
    {
        // TODO: Implement afterResolving() method.
    }

    /**
     * Alias a type to a different name.
     *
     * @param  string $abstract
     * @param  string $alias
     *
     * @return void
     */
    public function alias($abstract, $alias)
    {
        // TODO: Implement alias() method.
    }

    /**
     * Register a binding with the container.
     *
     * @param  string|array         $abstract
     * @param  \Closure|string|null $concrete
     * @param  bool                 $shared
     *
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        // TODO: Implement bind() method.
    }

    /**
     * Register a binding if it hasn't already been registered.
     *
     * @param  string               $abstract
     * @param  \Closure|string|null $concrete
     * @param  bool                 $shared
     *
     * @return void
     */
    public function bindIf($abstract, $concrete = null, $shared = false)
    {
        // TODO: Implement bindIf() method.
    }

    /**
     * Determine if the given type has been bound.
     *
     * @param  string $abstract
     *
     * @return bool
     */
    public function bound($abstract)
    {
        // TODO: Implement bound() method.
    }

    /**
     * Call the given Closure / class@method and inject its dependencies.
     *
     * @param  callable|string $callback
     * @param  array           $parameters
     * @param  string|null     $defaultMethod
     *
     * @return mixed
     */
    public function call($callback, array $parameters = [], $defaultMethod = null)
    {
        // TODO: Implement call() method.
    }

    /**
     * "Extend" an type in the container.
     *
     * @param  string   $abstract
     * @param  \Closure $closure
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function extend($abstract, Closure $closure)
    {
        // TODO: Implement extend() method.
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param  string $abstract
     * @param  mixed  $instance
     *
     * @return void
     */
    public function instance($abstract, $instance)
    {
        // TODO: Implement instance() method.
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string $abstract
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        // TODO: Implement make() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * Determine if the given type has been resolved.
     *
     * @param  string $abstract
     *
     * @return bool
     */
    public function resolved($abstract)
    {
        // TODO: Implement resolved() method.
    }

    /**
     * Register a new resolving callback.
     *
     * @param  string   $abstract
     * @param  \Closure $callback
     *
     * @return void
     */
    public function resolving($abstract, Closure $callback = null)
    {
        // TODO: Implement resolving() method.
    }

    /**
     * Register a shared binding in the container.
     *
     * @param  string               $abstract
     * @param  \Closure|string|null $concrete
     *
     * @return void
     */
    public function singleton($abstract, $concrete = null)
    {
        // TODO: Implement singleton() method.
    }

    /**
     * Assign a set of tags to a given binding.
     *
     * @param  array|string $abstracts
     * @param  array|mixed  ...$tags
     *
     * @return void
     */
    public function tag($abstracts, $tags)
    {
        // TODO: Implement tag() method.
    }

    /**
     * Resolve all of the bindings for a given tag.
     *
     * @param  array $tag
     *
     * @return array
     */
    public function tagged($tag)
    {
        // TODO: Implement tagged() method.
    }

    /**
     * Define a contextual binding.
     *
     * @param  string $concrete
     *
     * @return \Illuminate\Contracts\Container\ContextualBindingBuilder
     */
    public function when($concrete)
    {
        // TODO: Implement when() method.
    }
}
