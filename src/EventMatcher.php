<?php

declare(strict_types=1);

namespace Homework\RedisEvents;

final class EventMatcher
{
    public function __construct(private readonly EventStorage $storage)
    {
    }

    public function best(array $params): ?Event
    {
        foreach ($this->storage->allByPriority() as $event) {
            if ($event->matches($params)) {
                return $event;
            }
        }

        return null;
    }
}
