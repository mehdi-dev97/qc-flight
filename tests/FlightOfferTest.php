<?php

use Bl\QcFlight\FlightOffer;
use PHPUnit\Framework\TestCase;

class FlightOfferTest extends TestCase
{
    /**
     * test flight offer params is formated correctly.
     *
     * @return void
     */
    public function testApiParamsIscorrectlyFormated():void
    {
        $flightOffer = new FlightOffer(['CMN', 'CDG'], ['CDG'], ['2024-02-20', '2024-02-24', '2024-02-24'], 'USD', 1);

        $params = $flightOffer->apiParams();

        $this->assertIsArray($params);

    }
}
