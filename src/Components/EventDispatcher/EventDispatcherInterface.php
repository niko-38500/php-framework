<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher;

interface EventDispatcherInterface
{
    /**
     * @param callable[] $listeners
     */
    public function dispatch(array $listeners, Event $event): void;
}