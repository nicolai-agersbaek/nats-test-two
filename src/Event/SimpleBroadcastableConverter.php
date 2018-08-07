<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Event;

use SmartWeb\CloudEvents\VersionInterface;
use SmartWeb\Nats\BroadcastableInterface;
use SmartWeb\Nats\Payload\PayloadBuilder;
use SmartWeb\Nats\Payload\PayloadInterface;

/**
 * Simple broadcastable converter with fixed event source and CloudEvents specification version.
 */
class SimpleBroadcastableConverter implements BroadcastableConverterInterface
{
    
    /**
     * @var string
     */
    private $source;
    
    /**
     * @var VersionInterface
     */
    private $cloudEventsVersion;
    
    /**
     * @param string           $source
     * @param VersionInterface $cloudEventsVersion
     */
    public function __construct(string $source, VersionInterface $cloudEventsVersion)
    {
        $this->source = $source;
        $this->cloudEventsVersion = $cloudEventsVersion;
    }
    
    /**
     * @inheritDoc
     */
    public function convert(BroadcastableInterface $broadcastable) : PayloadInterface
    {
        return PayloadBuilder::create()
                             ->setEventId($event->getId())
                             ->setEventType($event->getName())
                             ->setData($event->getData())
                             ->setCloudEventsVersion($this->cloudEventsVersion)
                             ->setSource($this->source)
                             ->build();
    }
}
