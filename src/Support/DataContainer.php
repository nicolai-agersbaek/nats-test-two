<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Support;

/**
 * Abstract representation of a data container.
 */
abstract class DataContainer implements \ArrayAccess, \JsonSerializable, ArrayableInterface
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
    public function __get($name)
    {
        return $this[$name];
    }
    
    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        $this[$name] = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }
    
    /**
     * @inheritDoc
     */
    public function offsetExists($offset) : bool
    {
        return $this->elements->offsetExists($offset);
    }
    
    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->elements->offsetGet($offset);
    }
    
    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value) : void
    {
        $this->elements->offsetSet($offset, $value);
    }
    
    /**
     * @inheritDoc
     */
    public function offsetUnset($offset) : void
    {
        $this->elements->offsetUnset($offset);
    }
    
    /**
     * @inheritDoc
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }
    
    /**
     * @inheritDoc
     */
    public function toArray() : array
    {
        return $this->elements->getArrayCopy();
    }
}
