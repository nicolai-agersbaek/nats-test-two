<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Thrown when an event is not broadcastable over a NATS streaming connection.
 *
 * @api
 */
class EventNotBroadcastableException extends \InvalidArgumentException
{
    
    /**
     * @var Event
     */
    private $event;
    
    /**
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
        
        parent::__construct('The given event is not broadcastable.');
    }
    
    /**
     * @return Event
     */
    public function getEvent() : Event
    {
        return $this->event;
    }
}
