<?php

declare(strict_types=1);

namespace App\Components\Routing;

use App\Components\Routing\Definition\Route;

/**
 * @implements \IteratorAggregate<int, Route>
 */
class RouterCollection implements \IteratorAggregate, \Countable
{
    /** @var Route[] */
    private array $routes = [];

    /**
     * @param Route[] $routes
     */
    public function registerFromArray(array $routes): void
    {
        foreach ($routes as $route) {
            $this->register($route);
        }
    }

    public function register(Route $route): void
    {
        $this->routes[$route->getName()] = $route;
    }

    public function get(string $name): ?Route
    {
        return $this->routes[$name];
    }

    public function count(): int
    {
        return count($this->routes);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->routes);
    }
}