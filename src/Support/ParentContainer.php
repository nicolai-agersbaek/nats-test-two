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
class ParentContainer extends DataContainer
{
    
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $attributes['child'] = $this->resolveChild($attributes['child']);
        $attributes['date'] = $this->resolveDateTime($attributes['date']);
        
        parent::__construct($attributes);
    }
    
    /**
     * @param array $childData
     *
     * @return ChildContainer
     */
    private function resolveChild(array $childData) : ChildContainer
    {
        return new ChildContainer($childData);
    }
    
    /**
     * @param array $dateTimeData
     *
     * @return \DateTimeInterface
     */
    private function resolveDateTime(array $dateTimeData) : \DateTimeInterface
    {
        $dateTime = $this->createDateTime($dateTimeData);
        
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
    private function createDateTime(array $dateTimeData)
    {
        return \DateTime::createFromFormat(
            $dateTimeData['format'],
            $dateTimeData['time'],
            $dateTimeData['timezone'] ?? null
        );
    }
}
