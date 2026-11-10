<?php

declare(strict_types=1);

namespace Homework\RedisEvents;

interface EventStorage
{
    public function save(Event $event): void;
    public function clear(): void;
    public function allByPriority(): iterable;
}
