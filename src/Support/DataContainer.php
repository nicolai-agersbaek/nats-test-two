<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Support;

/**
 * Abstract representation of a data container.
 */
abstract class DataContainer implements \ArrayAccess, \JsonSerializable
{
    
    // TODO: Implementation of semi-open data container with configurable property access
    
    /**
     * @var \ArrayObject
     */
    private $elements;
    
    /**
     * @param array|null $attributes
     */
    protected function __construct(array $attributes = null)
    {
        $this->elements = new \ArrayObject($attributes ?? []);
    }
    
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
    final public function __set($name, $value)
    {
        $this[$name] = $value;
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
    public function toArray()
    {
        return $this->elements->getArrayCopy();
    }
}
