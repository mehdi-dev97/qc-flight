<?php

namespace Bl\QcFlight;

use InvalidArgumentException;
use Ramsey\Uuid\Type\Integer;

class FlightOffer extends Amadeuse
{

    /**
     * flight origins
     *
     * @var array|string
     */
    public array|string $origins;

    /**
     * flight destinations
     *
     * @var array|string
     */
    public array|string $destinations;

    /**
     * flight dates
     *
     * @var array|string
     */
    public array|string $dates;

    /**
     * flight adults
     *
     * @var int
     */
    public int $adults;

    /**
     * flight childrens
     *
     * @var int
     */
    public int $childrens;

    /**
     * flight infants
     *
     * @var int
     */
    public int $infants;

    /**
     * flight currency
     *
     * @var string
     */
    public string $currency;

    public function __construct(
        array|string $origins, 
        array|string $destinations, 
        array $dates,
        string $currency,
        int $adults, 
        int $childrens = 0, 
        int $infants = 0, 
    )
    {
        $this->origins = $origins;
        $this->destinations = $destinations;
        $this->dates = $dates;
        $this->adults = $adults;
        $this->childrens = $childrens;
        $this->infants = $infants;
        $this->currency = $currency;
    }

    /**
     * format flight params for search
     *
     * @return array
     */
    public function apiParams():array
    {

        $data = [];

        $originDestinations = [];

        if (count($this->dates) > 2 && (!is_array($this->origins) || !is_array($this->destinations))) {
            throw new InvalidArgumentException("Origins and destinations cannot be string if count of date is greather than 3.");
        }

        if (count($this->dates) > 2 && count($this->origins) < count($this->dates) ) {
            throw new InvalidArgumentException("Count of origins and destinations arrays should be equal to dates  array count");
        }

        $obj = $this;

        if (count($this->dates) <= 2) {

            $originDestinations = [array_map(function ($date, $key) use($obj) {

                return [
                    'id' => $key + 1,
    
                    'originLocationCode' => ($key + 1) == 1 ? $obj->origins : $obj->destinations,
    
                    'destinationLocationCode' => ($key + 1) > 1 ? $obj->origins : $obj->destinations,
    
                    'departureDateTimeRange' => ['date' => $date]
    
                ];
    
            }, array_values($this->dates), array_keys($this->dates))];


        } else {
            // [array_map(function ($date, $key) use($obj) {

            //     return [
            //         'id' => $key + 1,
    
            //         'originLocationCode' => $obj->origins[$key],
    
            //         'destinationLocationCode' => $obj->destinations[$key],
    
            //         'departureDateTimeRange' => ['date' => $date]
    
            //     ];
    
            // }, array_values($this->dates), array_keys($this->dates))];
        }

        return $data;
    }

    public function getAll():array
    {
        $params = $this->apiParams();

        return [];
    }

    public function get($id):array
    {
        return [];
    }
    
}
