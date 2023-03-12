<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher;

class ListenersRegistry implements ListenersRegistryInterface
{
    /**
     * @var callable[][]
     */
    public array $listeners;

    /**
     * @return callable[]
     */
    public function get(string $eventName): array
    {
        return $this->listeners[$eventName] ?? [];
    }

    public function set(string $eventName, callable $callback): void
    {
        $this->listeners[$eventName][] = $callback;
    }

    public function all(): array
    {
        return $this->listeners;
    }
}