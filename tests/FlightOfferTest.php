<?php

use Bl\QcFlight\Amadeuse;
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
        $flightOffer = new FlightOffer('CMN', 'CDG', ['2024-01-20', '2024-01-24'], 'USD', 'ECONOMY',1);

        $params = $flightOffer->apiParams();

        $this->assertIsArray($params);

    }

    /**
     * test flight offer is return data correctly.
     *
     * @return void
     */
    public function testApiGetFlightOffer():void
    {
        $amadeuse = new Amadeuse('AMADEUSE_ID', 'AMADEUSE_SECRET_KEY');

        $amadeuse->init();

        $flightOffer = new FlightOffer('CMN', 'CDG', ['2024-02-28', '2024-02-29'], 'USD', 'ECONOMY', 1);

        $offers = $flightOffer->getAll();

        $this->assertIsArray($offers->fetchArray());

    }

    /**
     * test flight set offer in session.
     *
     * @return void
     */
    public function testSetFlightOffer():void
    {
        $setOffer = FlightOffer::set(1);

        $this->assertTrue($setOffer);
    }

    /**
     * test flight get offer from session.
     *
     * @return void
     */
    public function testGetFlightOffer():void
    {
        $getOffer = FlightOffer::get();

        $this->assertIsArray($getOffer);

    }
}
