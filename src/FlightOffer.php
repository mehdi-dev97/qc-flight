<?php

namespace Bl\QcFlight;

use Ramsey\Uuid\Type\Integer;

class FlightOffer extends Amadeuse
{

    /**
     * flight origin
     *
     * @var string
     */
    public array|string $origins;

    /**
     * flight destination
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
        array|string $dates,
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
    protected function apiParams():array
    {

        $data = [];

        $originDestinations = [];

        $originDestinationIds = [];

        return $data;
    }

    public function getAll():array
    {
        print(parent::getToken());
        return [];
    }

    public function get($id):array
    {
        return [];
    }
    
}
