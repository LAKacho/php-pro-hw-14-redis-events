<?php

declare(strict_types=1);

namespace Homework\RedisEvents;

use InvalidArgumentException;

final class Event
{
    public function __construct(
        public readonly string $id,
        public readonly int $priority,
        public readonly array $conditions,
        public readonly array $payload,
    ) {
        if ($id === '' || $conditions === []) {
            throw new InvalidArgumentException('Event id and conditions must not be empty.');
        }
    }

    public function matches(array $params): bool
    {
        foreach ($this->conditions as $name => $value) {
            if (!array_key_exists($name, $params) || $params[$name] !== $value) {
                return false;
            }
        }

        return true;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'priority' => $this->priority,
            'conditions' => $this->conditions,
            'payload' => $this->payload,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['id'],
            (int) $data['priority'],
            (array) $data['conditions'],
            (array) $data['payload'],
        );
    }
}
