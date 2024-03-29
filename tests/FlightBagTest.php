<?php

use Bl\QcFlight\{
    Amadeuse,
    FlightBags,
    FlightOffer
};
use PHPUnit\Framework\TestCase;

class FlightBagTest extends TestCase
{
    public function testBagRequest()
    {    

        $amadeuse = new Amadeuse('AMADEUSE_ID', 'AMADEUSE_SECRET_KEY');

        $amadeuse->init();

        $flightOffer = new FlightOffer('CMN', 'CDG', ['2024-02-28', '2024-02-29'], 'USD', 'ECONOMY', 1);

        $flightOffer->getAll();

        FlightOffer::set(1);

        $quantities = (new FlightBags())->quantities();

        $this->assertIsArray($quantities);

        $this->assertGreaterThan(0, count($quantities));

    }

    public function testIfDataBagsIsSaved()
    {
        $this->assertNotNull(FlightBags::bags());
    }

    public function testDataIsSaved()
    {
        $flightOffer = new FlightBags();

        $flightOffer->save([
            1 => 2
        ]);
    }
}
