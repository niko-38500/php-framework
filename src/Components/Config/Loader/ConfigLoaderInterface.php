<?php

namespace App\Components\Config\Loader;

use App\Components\Config\Definition\FileConfigDefinition;

interface ConfigLoaderInterface
{
    public function load(string $path): FileConfigDefinition;

    public function getExtension(): string;
}