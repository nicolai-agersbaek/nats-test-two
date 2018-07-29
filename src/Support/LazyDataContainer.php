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
     * @var bool[]
     */
    private static $denormalizersInitialized = [];
    
    /**
     * The denormalizers used to lazily resolve values for this data container.
     *
     * @var DenormalizerInterface[]
     */
    private static $denormalizers = [];
    
    /**
     * Whether or not all values have been resolved.
     *
     * @var bool
     */
    private $resolvedAll = false;
    
    /**
     * @var bool[]
     */
    private $resolved = [];
    
    /**
     * @inheritDoc
     */
    final public function __get($name)
    {
        return $this->isResolved($name)
            ? $this[$name]
            : $this->resolve($name);
    }
    
    /**
     * @param string $key
     *
     * @return bool
     */
    private function isResolved(string $key) : bool
    {
        return $this->resolved[$key] = $this->resolved[$key] ?? false;
    }
    
    /**
     * @param string $key
     *
     * @return bool
     */
    private function hasDenormalizer(string $key) : bool
    {
        return \array_key_exists($key, self::$denormalizers);
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    private function resolve(string $key)
    {
        $this->resolved[$key] = true;
        $value = $this[$key];
        
        return $this->hasDenormalizer($key)
            ? $this->getDenormalizer($key)->denormalize($value)
            : $value;
    }
    
    /**
     * @param string $key
     *
     * @return DenormalizerInterface
     */
    private function getDenormalizer(string $key) : DenormalizerInterface
    {
        $this->initializeDenormalizersIfNeeded();
        
        return self::$denormalizers[$key];
    }
    
    private function initializeDenormalizersIfNeeded() : void
    {
        if (!self::$denormalizersInitialized[static::class]) {
            $this->initializeDenormalizers();
            self::$denormalizersInitialized[static::class] = true;
        }
    }
    
    private function initializeDenormalizers() : void
    {
        self::$denormalizers = static::getDenormalizers();
    }
    
    /**
     * @return DenormalizerInterface[]
     */
    abstract protected static function getDenormalizers() : array;
    
    /**
     * @inheritDoc
     */
    final public function toArray() : array
    {
        $this->resolveAllIfNeeded();
        
        return parent::toArray();
    }
    
    private function resolveAllIfNeeded() : void
    {
        if (!$this->resolvedAll) {
            $this->resolveAll();
            $this->resolvedAll = true;
        }
    }
    
    private function resolveAll() : void
    {
        foreach ($this as $key => $value) {
            if (!$this->isResolved($key)) {
                $this[$key] = $this->resolve($key);
            }
        }
    }
}
