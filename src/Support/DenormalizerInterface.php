<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Support;

/**
 * Definition of a class capable of denormalizing values into objects.
 */
interface DenormalizerInterface
{
    
    /**
     * Denormalize the given data into an object.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function denormalize($data);
}
