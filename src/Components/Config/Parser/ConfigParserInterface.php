<?php

namespace App\Components\Config\Parser;

use App\Components\Config\Exception\ParseConfigException;
use App\Components\Config\Schema\ConfigSchemaInterface;

interface ConfigParserInterface
{
    /**
     * @throws ParseConfigException
     */
    public function parseFromFile(string $path, ?ConfigSchemaInterface $schema = null): array;
}