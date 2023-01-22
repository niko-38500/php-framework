<?php

declare(strict_types=1);

namespace App\Components\Container\Tests\Tools\DataFixtures\DummyClass;

class ClassWithCircularReference
{
    public function __construct(public OtherClassWithCircularReference $otherClassWithCircularReference)
    {
    }
}