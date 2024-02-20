<?php

use Bl\QcFlight\Amadeuse;
use PHPUnit\Framework\TestCase;

class AmadeuseTest extends TestCase
{
    /**
     * test if response of request has been create amadeuse access token.
     *
     * @return void
     */
    public function testIfAmadeuseFlightTokenIsCreated():void
    {
        $amadeuse = new Amadeuse('BeVSwa8azGL6T9ZHwAK5Tu21JQ5PrXLp', 'l7P8zABLkmsFT9d7');

        $initializer = $amadeuse->init();

        $this->assertTrue($initializer);
    }

    /**
     * test if access token is saved in session.
     *
     * @return void
     */
    public function testIfAmadeuseTokenIsSavedOnSession():void
    {
        $this->assertIsString(Amadeuse::getToken());
    }

    /**
     * test if access token is saved in session.
     *
     * @return void
     */
    public function testUUIDisSavedOnSession():void
    {
        $this->assertIsString(Amadeuse::getUUID());
    }
}
