<?php
declare(strict_types = 1);


namespace SmartWeb\Nats;

use Nats\Connection;

/**
 * Class Service
 */
class Service implements ServiceInterface
{
    
    /**
     * @var Connection
     */
    private $connection;
    
    /**
     * Service constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    
    /**
     * @inheritDoc
     * @throws \Nats\Exception
     * @throws \Exception
     */
    public function run()
    {
        $this->connection->connect();
    
        $this->subscribe();
    
        $this->connection->wait(3);
        $this->connection->close();
    }
    
    private function subscribe()
    {
        $this->connection->subscribe(
            'foo',
            function ($message) {
                printf("Data: %s\r\n", $message->getBody());
            }
        );
    }
}
