<?php

declare(strict_types=1);

namespace App\Components\Routing\Tests;

use App\Components\Routing\Definition\Route;
use App\Components\Routing\RouterCollection;
use PHPUnit\Framework\TestCase;

class RouterCollectionTest extends TestCase
{
    public function testRegisterRoute(): void
    {
        $routerCollection = new RouterCollection();
        $route = new Route('/test', 'test');
        $routerCollection->register($route);

        self::assertSame($route, $routerCollection->get('test'));
    }

    public function testOnlyOneRouteWhenDuplicatedName(): void
    {
        $routerCollection = new RouterCollection();
        $routerCollection->register(new Route('/test', 'test'));
        $expectedRoute = new Route('/test', 'test', ['some' => 'params']);
        $routerCollection->register($expectedRoute);
        self::assertSame($expectedRoute, $routerCollection->get('test'));
    }
}