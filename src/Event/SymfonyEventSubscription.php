<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Event;

/**
 * Event subscription details for use with the Symfony framework.
 *
 * @api
 */
class SymfonyEventSubscription implements SymfonyEventSubscriptionInterface
{
    
    /**
     * @var string
     */
    private $eventName;
    
    /**
     * @var int|null
     */
    private $eventPriority;
    
    /**
     * @param string   $eventName
     * @param int|null $eventPriority
     */
    public function __construct(string $eventName, ?int $eventPriority = null)
    {
        $this->eventName = $eventName;
        $this->eventPriority = $eventPriority;
    }
    
    /**
     * @inheritDoc
     */
    public function getEventName() : string
    {
        return $this->eventName;
    }
    
    /**
     * @inheritDoc
     */
    public function getEventPriority() : ?int
    {
        return $this->eventPriority;
    }
}
