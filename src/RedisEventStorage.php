<?php

declare(strict_types=1);

namespace Homework\RedisEvents;

use Predis\ClientInterface;

final class RedisEventStorage implements EventStorage
{
    private const EVENTS = 'homework:events:data';
    private const PRIORITIES = 'homework:events:priority';

    public function __construct(private readonly ClientInterface $redis)
    {
    }

    public function save(Event $event): void
    {
        $json = json_encode($event->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        $this->redis->transaction(function ($transaction) use ($event, $json): void {
            $transaction->hset(self::EVENTS, $event->id, $json);
            $transaction->zadd(self::PRIORITIES, [$event->id => $event->priority]);
        });
    }

    public function clear(): void
    {
        $this->redis->del([self::EVENTS, self::PRIORITIES]);
    }

    public function allByPriority(): iterable
    {
        foreach ($this->redis->zrevrange(self::PRIORITIES, 0, -1) as $id) {
            $json = $this->redis->hget(self::EVENTS, $id);
            if (is_string($json)) {
                yield Event::fromArray(json_decode($json, true, flags: JSON_THROW_ON_ERROR));
            }
        }
    }
}
