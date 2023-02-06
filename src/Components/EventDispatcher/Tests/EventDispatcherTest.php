<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher\Tests;

use App\Components\EventDispatcher\EventDispatcher;
use App\Components\EventDispatcher\EventRegister;
use App\Components\EventDispatcher\Tests\Tools\DataFixtures\TestCustomEvent;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{
    public function testDispatchEvent(): void
    {
        $event = new TestCustomEvent();
        $eventRegister = new EventRegister();
        $eventDispatcher = new EventDispatcher($eventRegister);
    }
}