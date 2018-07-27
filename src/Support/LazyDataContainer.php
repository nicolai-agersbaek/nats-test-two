<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Support;

/**
 * Abstract representation of a data container with lazy resolution of nested object construction.
 */
abstract class LazyDataContainer extends DataContainer
{
    
    /**
     * Whether or not the denormalizers for this data container have been created.
     *
     * @var bool
     */
    private static $denormalizersInitialized = false;
    
    /**
     * The denormalizers used to lazily resolve values for this data container.
     *
     * @var callable[]
     */
    private static $denormalizers = [];
    
    /**
     * @var bool[]
     */
    private $resolved = [];
    
    
    /**
     * @inheritDoc
     */
    final public function __get($name)
    {
        return $this[$name];
    }
    
    /**
     * @inheritDoc
     */
    final public function __isset($name)
    {
        return $this->offsetExists($name);
    }
    
    /**
     * @inheritDoc
     */
    final public function offsetExists($offset) : bool
    {
        return $this->elements->offsetExists($offset);
    }
    
    /**
     * @inheritDoc
     */
    final public function offsetGet($offset)
    {
        return $this->elements->offsetGet($offset);
    }
    
    /**
     * @inheritDoc
     */
    final public function offsetSet($offset, $value) : void
    {
        $this->elements->offsetSet($offset, $value);
    }
    
    /**
     * @inheritDoc
     */
    final public function offsetUnset($offset) : void
    {
        $this->elements->offsetUnset($offset);
    }
    
    /**
     * @inheritDoc
     */
    final public function jsonSerialize()
    {
        return $this->toArray();
    }
    
    /**
     * @inheritDoc
     */
    final public function toArray()
    {
        return $this->elements->getArrayCopy();
    }
}
