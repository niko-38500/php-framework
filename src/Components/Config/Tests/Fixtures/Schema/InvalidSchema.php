<?php

declare(strict_types=1);

namespace App\Components\Config\Tests\Fixtures\Schema;

use App\Components\Config\Schema\ConfigSchemaInterface;

class InvalidSchema implements ConfigSchemaInterface
{
    public function schema(): array
    {
        return [];
    }
}