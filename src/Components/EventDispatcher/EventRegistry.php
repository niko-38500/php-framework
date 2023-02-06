<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher;

class EventRegistry
{
    /**
     * @var callable[]
     */
    public array $listeners;
}