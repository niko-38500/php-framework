<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher\Tests;

use App\Components\EventDispatcher\EventDispatcher;
use App\Components\EventDispatcher\EventsManager;
use App\Components\EventDispatcher\Exception\ListenerNotFoundException;
use App\Components\EventDispatcher\Tests\Tools\DataFixtures\TestCustomEvent;
use App\Components\EventDispatcher\Tests\Tools\DataFixtures\TestEventSubscriber;
use PHPUnit\Framework\TestCase;

class EventsManagerTest extends TestCase
{
    private function assertListeners(array $callbacks): void
    {
        foreach ($callbacks as $callback) {
            self::assertIsCallable($callback);
        }
    }
    public function testSubscribeEvent(): void
    {
        $eventSubscriber = new EventsManager(new EventDispatcher());

        $eventSubscriber->registerListener(TestCustomEvent::class, function (TestCustomEvent $event) { return $event; });

        self::assertTrue($eventSubscriber->hasListeners());
        self::assertTrue($eventSubscriber->hasListeners(TestCustomEvent::class));
        self::assertFalse($eventSubscriber->hasListeners('foo.bar'));
        $callback = $eventSubscriber->getListeners(TestCustomEvent::class);
        self::assertNotEmpty($callback);
        $this->assertListeners($callback);
    }

    public function testExceptionListenerNotExists(): void
    {
        $eventSubscriber = new EventsManager(new EventDispatcher());
        $this->expectException(ListenerNotFoundException::class);
        $eventSubscriber->getListeners('foo.bar');
    }

    public function testRegisterSubscriber(): void
    {
        $eventSubscriber = new EventsManager(new EventDispatcher());
        $event = new TestCustomEvent();
        $eventSubscriber->registerSubscriber(new TestEventSubscriber());

        self::assertEquals(0, $event->isCalled);
        $listeners = $eventSubscriber->getListeners($event::class);

        $this->assertListeners($listeners);
        self::assertCount(1, $listeners);

        current($listeners)($event);

        self::assertEquals(10, $event->isCalled);
    }
}