<?php

declare(strict_types=1);

namespace App\Components\Config;

use App\Components\Finder\Finder;

class Config
{
    public function __construct(
        private readonly Finder $finder
    ) {
    }

    public function loadConfig(string ...$basePath): void
    {
        foreach ($basePath as $path) {

        }
    }



    private function mergeConfigParameters(array ...$configs): array
    {
        $parameters = [];

        foreach ($configs as $configScope) {
            foreach ($configScope as $key => $value) {
                if (is_array($value)) {
                    $parameters[$key] = $this->mergeConfigParameters($value);
                    continue;
                }

                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }
}