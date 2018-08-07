<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Event;

use SmartWeb\Nats\BroadcastableInterface;
use SmartWeb\Nats\Connection\ConnectionInterface;
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
    private static $subscriptions = [];
    
    /**
     * @var ConnectionInterface
     */
    private $connection;
    
    /**
     * @var BroadcastableConverterInterface
     */
    private $converter;
    
    /**
     * @param ConnectionInterface             $connection
     * @param BroadcastableConverterInterface $converter
     */
    public function __construct(
        ConnectionInterface $connection,
        BroadcastableConverterInterface $converter
    ) {
        $this->connection = $connection;
        $this->converter = $converter;
    }
    
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents() : array
    {
        return \array_map(
            function (SymfonyEventSubscriptionInterface $subscription) : array {
                return [
                    'broadcastEvent',
                    $subscription->getEventPriority(),
                ];
            },
            self::$subscriptions
        );
    }
    
    /**
     * @return SymfonyEventSubscriptionInterface[]
     */
    public static function getSubscriptions() : array
    {
        return self::$subscriptions;
    }
    
    /**
     * @param SymfonyEventSubscriptionInterface[] $subscriptions
     */
    public static function setSubscriptions(array $subscriptions) : void
    {
        self::$subscriptions = [];
        
        self::addSubscriptions($subscriptions);
    }
    
    /**
     * @param SymfonyEventSubscriptionInterface[] $subscriptions
     */
    public static function addSubscriptions(array $subscriptions) : void
    {
        foreach ($subscriptions as $subscription) {
            self::addSubscription($subscription);
        }
    }
    
    /**
     * @param SymfonyEventSubscriptionInterface $subscription
     */
    public static function addSubscription(SymfonyEventSubscriptionInterface $subscription) : void
    {
        self::$subscriptions[$subscription->getEventName()] = $subscription;
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
        $this->connection->publish($event->getChannel(), $this->converter->convert($event));
    }
}
