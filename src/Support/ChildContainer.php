<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Support;

/**
 * Test container for performing benchmarks.
 *
 * @property string $name
 * @property string $description
 */
class ChildContainer extends DataContainer
{
    
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);
    }
}
