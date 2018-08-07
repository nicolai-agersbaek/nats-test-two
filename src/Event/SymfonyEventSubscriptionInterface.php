<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Event;

/**
 * Definition of an event subscription object for use with the Symfony framework.
 *
 * @api
 */
interface SymfonyEventSubscriptionInterface
{
    
    /**
     * @return string
     */
    public function getEventName() : string;
    
    /**
     * @return int|null
     */
    public function getEventPriority() : ?int;
}
