<?php
declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

$options = new \NatsStreaming\ConnectionOptions();
$options->setClientID('test');
$options->setClusterID('test-cluster');

$connection = new \NatsStreaming\Connection($options);

$connection->connect();

$subOptions = new \NatsStreaming\SubscriptionOptions();
$subOptions->setStartAt(\NatsStreamingProtos\StartPosition::LastReceived());

$subjects = 'some.subject';
$callback = function ($message) {
    \printf($message);
};

$sub = $connection->subscribe($subjects, $callback, $subOptions);

$sub->wait(1);

// not explicitly needed
$sub->unsubscribe(); // or $sub->close();

$connection->close();
