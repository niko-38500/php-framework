<?php

declare(strict_types=1);

namespace App\Components\EventDispatcher;

use App\Components\EventDispatcher\Exception\ListenerNotFoundException;

class EventsManager
{
    /** @var array<string, callable[]> */
    private array $listeners = [];

    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {}

    public function dispatch(Event $event): void
    {
        $this->eventDispatcher->dispatch($this->listeners[$event::class] ?? [], $event);
    }

    public function registerListener(string $name, callable $callback): void
    {
        $this->listeners[$name][] = $callback;
    }

    public function registerSubscriber(EventSubscriberInterface $eventSubscriber): void
    {
        foreach ($eventSubscriber->getSubscribedEvents() as $eventName => $eventCallbackName) {
            if (!method_exists($eventSubscriber, $eventCallbackName)) {
                throw new \LogicException(sprintf(
                'Method "%s::foobat()" not found please be sure that the method registered in ' .
                'getSubscribedEvents() for the event %s exists, has the correct name and is public',
                    $eventSubscriber::class,
                    $eventName
                ));
            }
            $this->registerListener($eventName, $eventSubscriber->$eventCallbackName(...));
        }
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
}