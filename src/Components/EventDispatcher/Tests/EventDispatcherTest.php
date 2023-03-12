<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher\Tests;

use App\Components\EventDispatcher\Event;
use App\Components\EventDispatcher\EventDispatcher;
use App\Components\EventDispatcher\EventsManager;
use App\Components\EventDispatcher\Tests\Tools\DataFixtures\TestCustomEvent;
use App\Components\EventDispatcher\Tests\Tools\DataFixtures\TestEventSubscriber;
use App\Components\EventDispatcher\Tests\Tools\DataFixtures\TestEventSubscriberWithTypo;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{
    public function testDispatchEvent(): void
    {
        $event = new TestCustomEvent();
        $eventsManager = new EventsManager(new EventDispatcher());
        $eventsManager->registerSubscriber(new TestEventSubscriber());
        $eventsManager->dispatch($event);

        self::assertEquals(10, $event->isCalled);
    }

    public function testDispatchNotRegisteredEvent(): void
    {
        $event = new TestCustomEvent();
        $eventsManager = new EventsManager(new EventDispatcher());
        $eventsManager->registerSubscriber(new TestEventSubscriber());
        $eventsManager->dispatch(new Event());

        self::assertEquals(0, $event->isCalled);
    }

    public function testDispatchEventWithMethodCallbackTypo(): void
    {
        $eventsManager = new EventsManager(new EventDispatcher());

        self::expectException(\LogicException::class);
        self::expectExceptionMessage(sprintf(
            'Method "%s::foobat()" not found please be sure that the method registered in ' .
            'getSubscribedEvents() for the event %s exists, has the correct name and is public',
            TestEventSubscriberWithTypo::class,
            TestCustomEvent::class
        ));

        $eventsManager->registerSubscriber(new TestEventSubscriberWithTypo());
    }
}