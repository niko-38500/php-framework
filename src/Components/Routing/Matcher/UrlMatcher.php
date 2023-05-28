<?php

declare(strict_types=1);

namespace App\Components\Routing\Matcher;

use App\Components\Routing\Definition\Route;
use App\Components\Routing\Exception\RouteNotFoundException;
use App\Components\Routing\RouterCollection;

class UrlMatcher
{
    public function __construct(
        private readonly RouterCollection $routerCollection
    ) {}

    /**
     * @throws RouteNotFoundException
     */
    public function matchRoute(string $url): Route
    {
        /** @var Route[] $matches */
        $matches = [];
        $url = $this->normalizeUrl($url);

        if ('/' === $url[0]) {
            $url = ltrim($url, '/');
        }

        foreach ($this->routerCollection as $route) {
            $path = ltrim($route->getPath(), '/');

            if ($url === $path) {
                return $route;
            } elseif (!$route->hasPathParameter()) {
                continue;
            }

            $splitRoute = explode('/', $path);
            $splitUrl = explode('/', $url);

            if (count($splitRoute) !== count($splitUrl)) {
                continue;
            }

            $shouldContinue = false;

            for ($i = 0; $i < count($splitRoute); $i++) {
                $currentRoutePath = $splitRoute[$i];
                $currentUrlPath = $splitUrl[$i];

                if (!preg_match('/\{.+}/', $currentRoutePath) && $currentUrlPath !== $currentRoutePath) {
                    $shouldContinue = true;
                    break;
                }
            }

            if ($shouldContinue) continue;

            $matches[] = $route;
        }

        $matchesLength = count($matches);

        if ($matchesLength === 1) {
            return current($matches);
        } elseif ($matchesLength === 0) {
            throw new RouteNotFoundException(sprintf('Not routes matches for the url : %s', $url));
        }

        // TODO : if multiple route matche add priority parameter for the routes
        return current($matches);
    }

    private function normalizeUrl(string $url): string
    {
        if ($pos = strpos($url, '?')) {
            $url = substr($url, 0, $pos);
        }

        if ($url[-1] === '/') {
            
//            TODO implement this in request component
//            $queryArgs = [];
//
//            $stringQueryArgs = $uriSplit[1];
//            $queryArgsArray = explode('&', $stringQueryArgs);
//
//            foreach ($queryArgsArray as $arg) {
//                $argSplit = explode('=', $arg);
//                $key = $argSplit[0];
//                $value = $argSplit[1];
//
//                $queryArgs[$key] = $value;
//            }
//
//            return [$newUri, $queryArgs];
        }

        return $url;
    }
}