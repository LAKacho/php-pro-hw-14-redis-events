<?php

declare(strict_types=1);

use Homework\RedisEvents\Event;
use Homework\RedisEvents\EventMatcher;
use Homework\RedisEvents\RedisEventStorage;
use Predis\Client;

require __DIR__ . '/../vendor/autoload.php';

$storage = new RedisEventStorage(new Client(getenv('REDIS_URL') ?: 'tcp://127.0.0.1:6379'));
$storage->clear();
$storage->save(new Event('first', 1000, ['param1' => 1], ['message' => 'first event']));
$storage->save(new Event('third', 3000, ['param1' => 1, 'param2' => 2], ['message' => 'third event']));

$event = (new EventMatcher($storage))->best(['param1' => 1, 'param2' => 2]);
echo json_encode($event?->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
