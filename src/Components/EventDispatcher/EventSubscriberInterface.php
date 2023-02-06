<?php

namespace App\Components\EventDispatcher;

interface EventSubscriberInterface
{
    /**
     * This interface allow to register a set of custom events
     *
     * Example: return [ 'eventName' => 'methodName' ]
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array;
}