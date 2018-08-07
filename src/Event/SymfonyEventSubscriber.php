<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Event;

use SmartWeb\CloudEvents\VersionInterface;
use SmartWeb\Nats\BroadcastableInterface;
use SmartWeb\Nats\Connection\ConnectionInterface;
use SmartWeb\Nats\Payload\PayloadBuilder;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber that converts received events to payload instances and
 * dispatches them as messages on the given NATS streaming connection.
 * Designed for use with the Symfony framework.
 */
final class SymfonyEventSubscriber implements EventSubscriberInterface
{
    
    /**
     * @var SymfonyEventSubscriptionInterface[]
     */
    private static $subscribedEvents = [];
    
    /**
     * @var ConnectionInterface
     */
    private $connection;
    
    /**
     * @var string
     */
    private $eventSource;
    
    /**
     * @var VersionInterface
     */
    private $cloudEventsVersion;
    
    /**
     * @param ConnectionInterface $connection
     * @param string              $eventSource
     * @param VersionInterface    $cloudEventsVersion
     */
    public function __construct(
        ConnectionInterface $connection,
        string $eventSource,
        VersionInterface $cloudEventsVersion
    ) {
        $this->connection = $connection;
        $this->eventSource = $eventSource;
        $this->cloudEventsVersion = $cloudEventsVersion;
    }
    
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        throw new \BadMethodCallException(__METHOD__ . ' not yet implemented!');
    }
    
    /**
     * @param SymfonyEventSubscriptionInterface[] $events
     */
    public static function setSubscribedEvents(array $events) : void
    {
        self::$subscribedEvents = [];
        
        foreach ($events as $subscription) {
            self::addEventSubscription($subscription);
        }
    }
    
    /**
     * @param SymfonyEventSubscriptionInterface $subscription
     */
    private static function addEventSubscription(SymfonyEventSubscriptionInterface $subscription) : void
    {
        self::$subscribedEvents[$subscription->getEventName()] = $subscription;
    }
    
    /**
     * @param Event $event
     */
    public function onBroadcastableEvent(Event $event) : void
    {
        if ($event instanceof BroadcastableInterface) {
            $this->broadcastEvent($event);
        } else {
            throw new EventNotBroadcastableException($event);
        }
    }
    
    /**
     * @param BroadcastableInterface $event
     */
    private function broadcastEvent(BroadcastableInterface $event) : void
    {
        $payload = PayloadBuilder::create()
                                 ->setEventId($event->getId())
                                 ->setEventType($event->getName())
                                 ->setData($event->getData())
                                 ->setCloudEventsVersion($this->cloudEventsVersion)
                                 ->setSource($this->eventSource)
                                 ->build();
        
        $this->connection->publish($event->getChannel(), $payload);
    }
}
