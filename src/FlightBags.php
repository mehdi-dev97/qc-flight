<?php

namespace Bl\QcFlight;

use Exception;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class FlightBags extends FlightOffer
{

    public function __construct()
    {
        
    }

    protected function format($bags)
    {
        $data = [];

        foreach ($bags as $bag) {
            $data[$bag['quantity']] = [
                'qt' => $bag['quantity'],
                'price' => $bag['price']['amount'],
                'travelerIds' => implode(',', $bag['travelerIds']),
            ];
        }

        ksort($data);

        return $data;
    }

    public function quantities():array
    {

        $http = new Client();

        try {

            $http = $http->post('https://' . ($this->test ? 'test.' : null) . 'travel.api.amadeus.com/v1/shopping/flight-offers/pricing?include=bags', [
                'headers' => [
                    'Content-Type' => 'application/json',

                    'Authorization' => self::getToken(),

                    'Ama-Client-Ref' => self::getUUID(),
                ],

                'body' => json_encode([
                    'data' => [
                        'type' => 'flight-offers-pricing',

                        'flightOffers' => parent::get(),
                    ],
                ]),
            ]);

            $response = $http->getBody()->getContents();

            $response = json_decode($response, true);

            if (isset($response['warnings']) || !isset($response['included'])) {
            
                return [];
            
            }

            $quantities = $this->format($response['included']['bags']);
            
            $session = new Session();

            $session->set('bags_offer', $quantities);

            return $quantities;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function bags()
    {
        $session = new Session();
        return $session->get('bags_offer') ?? null;
    }

    public function save(array $travelersQuantity):void
    {
        $flights = json_decode(json_encode($this->get()), true);

        $travelerPricings = $flights[0]['travelerPricings'];
        
        $bags = $this->bags();

        $totalBags = 0;

        foreach ($travelersQuantity as $travelerId => $quantity) {
            foreach ($travelerPricings as $key => $traveler) {
                if ($traveler['travelerId'] == $travelerId) {
                    if ($quantity['quantity'] > 0) {
                        $travelerPricings[$key]['fareDetailsBySegment'][0]['additionalServices']['chargeableCheckedBags'] = [
                            'quantity' => $quantity['quantity'],
                        ];

                        if (isset($traveler['fareDetailsBySegment'][1])) {
                            $travelerPricings[$key]['fareDetailsBySegment'][1]['additionalServices']['chargeableCheckedBags'] = [
                                'quantity' => $quantity['quantity'],
                            ];
                        }

                        $totalBags += $bags[$quantity['quantity']]['price'] * count($traveler['fareDetailsBySegment']);
                    }
                }
            }
        }

        $grandTotal = $flights[0]['price']['grandTotal'];

        $oldBagTotal = 0;

        if (isset($flights[0]['price']['additionalServices'])) {
            foreach ($flights[0]['price']['additionalServices'] as $key => $service) {

                if ($service['type'] == 'CHECKED_BAGS') {

                    $oldBagTotal = $flights[0]['price']['additionalServices'][$key]['amount'];

                    unset($flights[0]['price']['additionalServices'][$key]);
                }
            }
            if(count($flights[0]['price']['additionalServices']) <= 0){
                unset($flights[0]['price']['additionalServices']);
            }
        }

        $flights[0]['travelerPricings'] = $travelerPricings;

        $grandTotal -= $oldBagTotal;

        $flights[0]['price']['grandTotal'] = (string) ($grandTotal + $totalBags);

        $http = new Client();

        $offer = [
            'data' => [
                'type' => 'flight-offers-pricing',

                'flightOffers' => $flights,
            ],
        ];

        try {
            $http = $http->post('https://' . ($this->test ? 'test.' : null) . 'travel.api.amadeus.com/v1/shopping/flight-offers/pricing', [
                'headers' => [
                    'Content-Type' => 'application/json',

                    'Authorization' => self::getToken(),

                    'Ama-Client-Ref' => self::getUUID(),
                ],

                'body' => json_encode($offer),
            ]);

            $response = $http->getBody()->getContents();

            $response = json_decode($response, true);

            if (isset($response['warnings'])) {

                throw new Exception($response['warnings']);
            }

            $session = new Session();

            $session->set('flight_offer', $response['data']['flightOffers']);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }
}
