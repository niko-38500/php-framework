<?php

declare(strict_types=1);

namespace App\Components\Container\Tests\Tools\DataFixtures\DummyClass;

class ClassWithDeepDependencies
{
    public function __construct(public ClassWithDependencies $classWithDependencies)
    {
    }
}