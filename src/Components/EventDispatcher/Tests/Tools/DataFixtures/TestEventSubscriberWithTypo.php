<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher\Tests\Tools\DataFixtures;

use App\Components\EventDispatcher\EventSubscriberInterface;

class TestEventSubscriberWithTypo implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            TestCustomEvent::class => 'foobat'
        ];
    }

    public function foobar(TestCustomEvent $event): void
    {}
}