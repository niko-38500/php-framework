<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher\Tests\Tools\DataFixtures;

use App\Components\EventDispatcher\Event;

class TestCustomEvent extends Event
{
    public int $isCalled = 0;
}