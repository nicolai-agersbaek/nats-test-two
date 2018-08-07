<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Event;

use SmartWeb\Nats\BroadcastableInterface;
use SmartWeb\Nats\Payload\PayloadInterface;

/**
 * Definition of objects capable of converting a broadcastable object to a payload instance.
 *
 * @api
 */
interface BroadcastableConverterInterface
{
    
    /**
     * Convert the given broadcastable to a payload.
     *
     * @param BroadcastableInterface $broadcastable
     *
     * @return PayloadInterface
     */
    public function convert(BroadcastableInterface $broadcastable) : PayloadInterface;
}
