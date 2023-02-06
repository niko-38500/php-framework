<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher;

class EventDispatcher implements EventDispatcherInterface
{
    public function __construct()
    {
    }

    public function dispatch(Event $event): void
    {
        $eventName = $event::class;

    }
}