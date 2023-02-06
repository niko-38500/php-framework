<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher;

interface EventDispatcherInterface
{
    public function dispatch(Event $event): void;
}