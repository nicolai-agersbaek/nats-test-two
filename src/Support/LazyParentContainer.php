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
class LazyParentContainer extends LazyDataContainer
{
    
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
