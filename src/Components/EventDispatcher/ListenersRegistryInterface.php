<?php

namespace App\Components\EventDispatcher;

interface ListenersRegistryInterface
{
    /**
     * @return callable[]
     */
    public function get(string $eventName): array;

    /**
     * @return callable[][]
     */
    public function all(): array;

    public function set(string $eventName, callable $callback): void;
}