<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher\Tests\Tools\DataFixtures;

use App\Components\EventDispatcher\EventSubscriberInterface;

class TestEventSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            TestCustomEvent::class => 'doSomething',
        ];
    }

    public function doSomething(TestCustomEvent $event): void
    {
        $event->isCalled = 10;
    }
}