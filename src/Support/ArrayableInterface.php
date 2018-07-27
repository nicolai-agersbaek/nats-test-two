<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Support;

/**
 * Definition of an object that has an array representation.
 */
interface ArrayableInterface
{
    
    /**
     * Get the array representation of this object.
     *
     * @return array
     */
    public function toArray() : array;
}
