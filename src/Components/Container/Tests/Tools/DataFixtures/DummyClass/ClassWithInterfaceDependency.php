<?php

declare(strict_types=1);

namespace App\Components\Container\Tests\Tools\DataFixtures\DummyClass;

use App\Components\Container\Tests\Tools\DataFixtures\DummyInterface\IOCInterface;

class ClassWithInterfaceDependency
{
    public function __construct(public IOCInterface $ioc)
    {
    }
}