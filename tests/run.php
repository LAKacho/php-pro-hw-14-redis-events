<?php

declare(strict_types=1);

require __DIR__ . '/../src/Event.php';
require __DIR__ . '/../src/EventStorage.php';
require __DIR__ . '/../src/InMemoryEventStorage.php';
require __DIR__ . '/../src/EventMatcher.php';

use Homework\RedisEvents\Event;
use Homework\RedisEvents\EventMatcher;
use Homework\RedisEvents\InMemoryEventStorage;

$storage = new InMemoryEventStorage();
$storage->save(new Event('first', 1000, ['param1' => 1], ['name' => 'first']));
$storage->save(new Event('second', 2000, ['param1' => 2, 'param2' => 2], ['name' => 'second']));
$storage->save(new Event('third', 3000, ['param1' => 1, 'param2' => 2], ['name' => 'third']));

$matcher = new EventMatcher($storage);

if ($matcher->best(['param1' => 1, 'param2' => 2])?->id !== 'third') {
    throw new RuntimeException('Highest-priority matching event expected.');
}
if ($matcher->best(['param1' => 1])?->id !== 'first') {
    throw new RuntimeException('Single-condition event expected.');
}
if ($matcher->best(['param1' => 9]) !== null) {
    throw new RuntimeException('No event expected.');
}

$storage->clear();
if ($matcher->best(['param1' => 1]) !== null) {
    throw new RuntimeException('Storage must be empty.');
}

echo "All tests passed\n";
