<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Support;

/**
 * Test container for performing benchmarks.
 *
 * @property string             $name
 * @property ChildContainer     $child
 * @property \DateTimeInterface $date
 */
class LazyParentContainer extends DataContainer
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
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);
    }
    
    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        return $this->shouldResolve($name)
            ? $this->resolve($name)
            : $this[$name];
    }
    
    /**
     * @param string $key
     *
     * @return bool
     */
    private function shouldResolve(string $key) : bool
    {
        return !$this->isResolved($key) && $this->hasDenormalizer($key);
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
        return \array_key_exists($key, static::$denormalizers);
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    private function resolve(string $key)
    {
        return $this->getDenormalizer($key)($this[$key]);
    }
    
    /**
     * @param string $key
     *
     * @return callable
     */
    private function getDenormalizer(string $key) : callable
    {
        $this->initializeDenormalizersIfNeeded();
        
        return static::$denormalizers[$key];
    }
    
    private function initializeDenormalizersIfNeeded() : void
    {
        if (!static::$denormalizersInitialized) {
            $this->initializeDenormalizers();
            static::$denormalizersInitialized = true;
        }
    }
    
    private function initializeDenormalizers() : void
    {
        static::$denormalizersInitialized = static::getDenormalizers();
    }
    
    /**
     * @return callable[]
     */
    protected static function getDenormalizers() : array
    {
        return [
            'child' => ['self', 'resolveChild'],
            'date'  => ['self', 'resolveDate'],
        ];
    }
    
    /**
     * @param array $childData
     *
     * @return ChildContainer
     */
    private static function resolveChild(array $childData) : ChildContainer
    {
        return new ChildContainer($childData);
    }
    
    /**
     * @param array $dateTimeData
     *
     * @return \DateTimeInterface
     */
    private static function resolveDate(array $dateTimeData) : \DateTimeInterface
    {
        $dateTime = self::createDateTime($dateTimeData);
        
        if ($dateTime === false) {
            $errors = \DateTime::getLastErrors();
            \var_dump($errors);
            throw new \InvalidArgumentException('Invalid DateTime data');
        }
        
        return $dateTime;
    }
    
    /**
     * @param array $dateTimeData
     *
     * @return bool|\DateTime
     */
    private static function createDateTime(array $dateTimeData)
    {
        return \DateTime::createFromFormat(
            $dateTimeData['format'],
            $dateTimeData['time'],
            $dateTimeData['timezone'] ?? null
        );
    }
}
