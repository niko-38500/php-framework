<?php

declare(strict_types=1);

namespace App;

use App\Components\Finder\Finder;

class Test
{
    public function __construct(string $path)
    {
        $finder = new Finder();
        $finder->in($path, '');

    }
}