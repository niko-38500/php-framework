<?php

declare(strict_types=1);

namespace App\Components\Container\Tests\Tools\DataFixtures\DummyClass;

class ClassWithNonTypedParameter
{
    public function __construct($nonTypedArgs)
    {
    }
}