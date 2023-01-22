<?php

declare(strict_types=1);

namespace App\Components\Container\Tests\Tools\DataFixtures\DummyClass;

class OtherClassWithCircularReference
{
    public function __construct(public ClassWithCircularReference $circularReference)
    {
    }
}