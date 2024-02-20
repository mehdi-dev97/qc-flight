<?php

namespace Bl\QcFlight;

use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * flight currency
     *
     * @var string
     */
    public string $currency;

    /**
     * flight cabin
     *
     * @var string
     */
    public string $cabin;

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
     * flight offers
     *
     * @var mixed
     */
    protected mixed $offers;

    public function __construct(
        array|string $origins, 
        array|string $destinations, 
        array $dates,
        string $currency,
        string $cabin,
        int $adults, 
        int $childrens = 0, 
        int $infants = 0, 
    )
    {
        $this->origins = $origins;
        $this->destinations = $destinations;
        $this->dates = $dates;
        $this->currency = $currency;
        $this->cabin = $cabin;
        $this->adults = $adults;
        $this->childrens = $childrens;
        $this->infants = $infants;
    }

    /**
     * format flight params for search
     *
     * @return array
     */
    public function apiParams():array
    {

        $originDestinations = [];

        if (count($this->dates) > 2 && (!is_array($this->origins) || !is_array($this->destinations))) {
            throw new InvalidArgumentException("Origins and destinations cannot be string if count of date is greather than 3.");
        }

        if (count($this->dates) > 2 && count($this->origins) < count($this->dates) ) {
            throw new InvalidArgumentException("Count of origins and destinations arrays should be equal to dates  array count");
        }

        $self = $this;

        if (count($this->dates) <= 2) {

            $originDestinations = array_map(function ($date, $key) use($self) {

                return [
                    'id' => $key + 1,
    
                    'originLocationCode' => ($key + 1) == 1 ? $self->origins : $self->destinations,
    
                    'destinationLocationCode' => ($key + 1) > 1 ? $self->origins : $self->destinations,
    
                    'departureDateTimeRange' => ['date' => $date]
    
                ];
    
            }, array_values($this->dates), array_keys($this->dates));

        }

        $originDestinationIds = array_map(function ($data) {
            return $data['id'];
        }, $originDestinations);

        $data = [
            'currencyCode' => $this->currency,

            'originDestinations' => $originDestinations,

            'travelers' => travelers($this->adults, $this->childrens, $this->infants),

            'sources' => ['GDS'],

            'searchCriteria' => [

                /*'addOneWayOffers' => true,*/

                'pricingOptions' => [

                    'includedCheckedBagsOnly' => true,
                    'fareType' => ['PUBLISHED']
                    /*  'fareType' => ['PUBLISHED','NEGOTIATED']*/
                ],
                'additionalInformation' => [
                    'chargeableCheckedBags' => false,
                ],

                'flightFilters' => [
                    'connectionRestriction' => [
                        'maxNumberOfConnections' => 0
                    ],
                    'cabinRestrictions' => [

                        array(
                            'cabin' => $this->cabin,
                            'coverage' => 'MOST_SEGMENTS',
                            'originDestinationIds' => $originDestinationIds
                        )

                    ],
                    "carrierRestrictions" => [
                        "excludedCarrierCodes" => [
                          "6X",
                          "5O"
                        ]
                    ]
                ],
            ]

        ];

        return $data;
    }

    /**
     * send request to get all flight offers and save it in session.
     *
     * @return $this
     */
    public function getAll()
    {
        $params = $this->apiParams();
        
        $http = new Client();
        
        try {

            $http = $http->post('https://' . ($this->test ? 'test.' : null) . 'travel.api.amadeus.com/v2/shopping/flight-offers', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => Amadeuse::getToken(),
                    'Ama-Client-Ref' => Amadeuse::getUUID()
                ],
                'body' => json_encode($params)
            ]);

            $response = $http->getBody()
            ->getContents();

            $session = new Session();

            $offers = json_decode($response);

            $session->remove('flight_offers');

            $session->set('flight_offers', $offers);

            $this->offers = $offers;

            return $this;

        } catch(Exception $e) {

            throw new Exception($e->getMessage());
        
        }
    }

    /**
     * fetch all data returned to array.
     *
     * @return array
     */
    public function fetchArray():array
    {
        $offers = json_decode(json_encode($this->offers), true);
     
        return $offers ?? [];
    }

    /**
     * fetch all data returned to object.
     *
     * @return array
     */
    public function fetchObject()
    {
     
        return $this->offers ?? null;
    }

    /**
     * save flight offer to use it after.
     * @param int $id
     *
     * @return bool
     */
    public static function set(int $id):bool
    {
        $session = new Session();

        $offers = $session->get('flight_offers');

        $offers = array_filter($offers->data, function ($offer) use($id) {

            return $offer->id == $id;

        });

        sort($offers);

        if (count($offers) < 1) {
            return false;
        }

        $session->set('flight_offer', $offers);

        return true;
    }

    /**
     * get saved flight offer.
     *
     * @return array|null
     */
    public static function get():array|null
    {
        $session = new Session();

        return $session->get('flight_offer') ?? null;
    }
    
    
}
