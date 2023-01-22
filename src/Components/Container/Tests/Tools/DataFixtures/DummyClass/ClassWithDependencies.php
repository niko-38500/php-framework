<?php

declare(strict_types=1);

namespace App\Components\Container\Tests\Tools\DataFixtures\DummyClass;

class ClassWithDependencies
{
    public function __construct(public ClassWithNoDependencies $classWithNoDependencies)
    {
    }
}