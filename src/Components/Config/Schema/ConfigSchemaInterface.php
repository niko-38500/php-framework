<?php

declare(strict_types=1);

namespace App\Components\Config\Schema;

interface ConfigSchemaInterface
{
    public function schema(): array;
}