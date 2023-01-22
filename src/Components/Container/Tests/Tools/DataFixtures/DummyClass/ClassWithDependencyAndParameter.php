<?php

declare(strict_types=1);

namespace App\Components\Container\Tests\Tools\DataFixtures\DummyClass;

class ClassWithDependencyAndParameter
{
    public function __construct(public ClassWithDependencies $classWithDependencies, public string $boundParameter)
    {
    }
}