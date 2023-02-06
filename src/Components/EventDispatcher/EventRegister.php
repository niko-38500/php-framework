<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher;

use App\Components\EventDispatcher\Exception\ListenerNotFoundException;

class EventRegister
{
    /** @var array<string, callable[]> */
    private array $listeners = [];

    public function registerListener(string $name, callable $callback): void
    {
        $this->listeners[$name][] = $callback;
    }

    public function hasListeners(?string $name = null): bool
    {
        if (is_null($name)) {
            return !empty($this->listeners);
        }

        return !empty($this->listeners[$name]);
    }

    /**
     * @param string $name
     * @return callable[]
     *
     * @throws ListenerNotFoundException
     */
    public function getListeners(string $name): array
    {
        if (!$this->hasListeners($name)) {
            throw new ListenerNotFoundException(sprintf('Can not get listeners, key "%s" not found', $name));
        }
        return $this->listeners[$name];
    }

    public function registerSubscriber(EventSubscriberInterface $eventSubscriber): void
    {
        foreach ($eventSubscriber->getSubscribedEvents() as $eventName => $eventCallback) {
            $this->registerListener($eventName, $eventSubscriber->$eventCallback(...));
        }
    }
}