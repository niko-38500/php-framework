<?php

declare(strict_types=1);

namespace App\Components\Routing\Tests\Matcher;

use App\Components\Routing\Definition\Route;
use App\Components\Routing\Exception\RouteNotFoundException;
use App\Components\Routing\Matcher\UrlMatcher;
use App\Components\Routing\RouterCollection;
use PHPUnit\Framework\TestCase;

class UrlMatcherTest extends TestCase
{
    private RouterCollection $routerCollection;
    /** @var Route[] */
    private array $routes;

    protected function setUp(): void
    {
        $this->routes = [
            new Route('/test', 'test'),
            new Route('/test/other', 'test_other', ['param' => 'test']),
            new Route('/test/other/another', 'another_test_other'),
            new Route('/test/{param}', 'test_param', ['_controller' => 'a controller class']),
            new Route('/test/{with}/{params}', 'test_with_params'),
        ];

        $routerCollection = new RouterCollection();
        $routerCollection->registerFromArray($this->routes);

        $this->routerCollection = $routerCollection;
    }

    public static function routeProvider(): \Iterator
    {
        yield '#1' => ['/test', 0, null];

        yield '#2' => ['/test/other', 1, null];

        yield '#3' => ['/test/other/another', 2, null];

        yield '#4' => ['/test/slug', 3, ['param' => 'slug']];

        yield '#5' => ['/test/slug/1', 4, ['with' => 'slug', 'params' => '1']];

        yield '#6' => ['/test/slug/1?query=arg', 4, ['with' => 'slug', 'params' => '1']];
    }

    /**
     * @dataProvider routeProvider
     */
    public function testMatchRoute(string $uri, int $expectedRoute, ?array $expectedAddedParams): void
    {
        $urlMatcher = new UrlMatcher($this->routerCollection);

        $actualRoute = $urlMatcher->matchRoute($uri);
        $expectedRoute = $this->routes[$expectedRoute];
        $expectedRoute->addParams($expectedAddedParams ?? []);

        self::assertSame($expectedRoute, $actualRoute);
    }

    public function testThatRouteNotFoundThrowException(): void
    {
        $urlMatcher = new UrlMatcher($this->routerCollection);

        $this->expectException(RouteNotFoundException::class);
        $urlMatcher->matchRoute('/un/deux/trois/quatre');
    }
}