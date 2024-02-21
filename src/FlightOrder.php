<?php

namespace Bl\QcFlight;

use Exception;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class FlightOrder extends Amadeuse
{
    /**
     * travelers details
     *
     * @var array
     */
    public array $travelers = [];

    /**
     * contact details for flight responsable
     *
     * @var array
     */
    public array $contacts = [];

    /**
     * contact details for flight responsable
     *
     * @var string
     */
    public string $reference = '';

    /**
     * our object constructor
     *
     * @param array $travelers
     * @param array $contacts
     * @param string $reference
     */
    public function __construct(array $travelers = [], array $contacts = [], string $reference = '')
    {
        $this->travelers = $travelers;
        $this->contacts = $contacts;
        $this->reference = $reference;
    }

    /**
     * Create a new order
     *
     * @return void
     */
    public function set():void
    {

        $order = [
            "data" => [
                "type" => "flight-order",
                "flightOffers" => [FlightOffer::get()],
                "travelers" => $this->travelers,
                "remarks" => [
                    'general' => [
                        0 => [
                            "subType" => "GENERAL_MISCELLANEOUS",
                            "text" => "ONLINE BOOKING"
                        ]
                    ]
                ],
                "formOfPayments" => [
                    0 => [
                        "other" => [
                            "method" => "CASH",
                            "flightOfferIds" => [
                                0 => "1"
                            ]
                        ]
                    ]
                ],
                "contacts" => [0 => $this->contacts],
            ]
        ];

        $http = new Client();

        try {

            $http = $http->post('https://' . ($this->test ? 'test.' : null) . 'travel.api.amadeus.com/v1/booking/flight-orders', [

                'headers' => [

                    'Content-Type' => 'application/json',

                    'Authorization' => self::getToken(),

                    'Ama-Client-Ref' => self::getUUID()

                ],

                'body' => json_encode($order)

            ]);

            $response = $http->getBody()
                ->getContents();

            $response = json_decode($response, true);

            $session = new Session();

            $session->set('order_id', $response['data']['id']);

        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    public static function id()
    {
        $session = new Session();

        return $session->get('order_id') ?? null;
    }

    /**
     * Create ticketing flight
     *
     * @return array
     */
    public function ticketing():array
    {
        $http = new Client();

        try {

            $http = $http->post('https://' . ($this->test ? 'test.' : null) . 'travel.api.amadeus.com/v1/booking/flight-orders/' . self::id() . '/issuance', [
                'headers' => [

                    'Content-Type' => 'application/json',

                    'Authorization' => self::getToken(),

                    'Ama-Client-Ref' => self::getUUID()

                ]
            ]);

            $response = $http->getBody()
                ->getContents();

            $response = json_decode($response, true);

            return $response;

        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * get all data info about flight using reference
     *
     * @return array
     */
    public function get():array
    {
        $client = new Client();

        try {

            $http = $client->post('https://' . ($this->test ? 'test.' : null) . 'travel.api.amadeus.com/v1/booking/flight-orders/by-reference?reference=' . $this->reference . '&originSystemCode=GDS', [
                'headers' => [

                    'Content-Type' => 'application/vnd.amadeus+json',

                    'Authorization' => self::getToken(),

                    'Ama-Client-Ref' => self::getUUID()

                ]
            ]);

            $response = $http->getBody()
                ->getContents();

            $response = json_decode($response, true);

            return $response;
            
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}