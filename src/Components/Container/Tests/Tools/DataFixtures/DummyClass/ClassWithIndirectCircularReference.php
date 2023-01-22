<?php

declare(strict_types=1);

namespace App\Components\Container\Tests\Tools\DataFixtures\DummyClass;

class ClassWithIndirectCircularReference
{
    public function __construct(
        public ClassWithDeepDependencies $classWithDeepDependencies,
        public ClassWithCircularReference $classWithCircularReference
    ) {
    }
}