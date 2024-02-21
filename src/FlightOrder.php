<?php

namespace Bl\QcFlight;

class FlightOrder extends FlightOffer
{
    /**
     * flight destinations
     *
     * @var array
     */
    public array $travelers = [];

    /**
     * our object constructor
     *
     * @param $travelers
     */

    public function __construct(array $travelers = [])
    {
        
    }

}