<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @param callable[] $listeners
     */
    public function dispatch(array $listeners, Event $event): void
    {
        foreach ($listeners as $listener) {
            $listener($event);
        }
    }
}