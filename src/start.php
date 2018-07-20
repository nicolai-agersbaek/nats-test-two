<?php
declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';


$options = new \Nats\ConnectionOptions(
    [
        'port' => 4222,
    ]
);
$client = new \Nats\Connection();

$service = new \SmartWeb\Nats\Service($client);

$service->run();
