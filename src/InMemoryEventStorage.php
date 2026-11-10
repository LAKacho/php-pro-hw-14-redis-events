<?php

declare(strict_types=1);

namespace Homework\RedisEvents;

final class InMemoryEventStorage implements EventStorage
{
    private array $events = [];

    public function save(Event $event): void
    {
        $this->events[$event->id] = $event;
    }

    public function clear(): void
    {
        $this->events = [];
    }

    public function allByPriority(): iterable
    {
        $events = array_values($this->events);
        usort($events, static fn (Event $left, Event $right): int => $right->priority <=> $left->priority);
        return $events;
    }
}
