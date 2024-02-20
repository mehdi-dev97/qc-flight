<?php

namespace Bl\QcFlight;

use Exception;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class Amadeuse
{
    /**
     * api id
     *
     * @var string
     */
    protected string $id;
    
    /**
     * api secret key
     *
     * @var string
     */
    protected string $secretKey;

    /**
     * env type
     *
     * @var bool
     */
    protected bool $test;

    /**
     * our object constructor
     *
     * @param $id
     * @param $secretKey
     * @param $test
     */

    public function __construct(string $id, string $secretKey, bool $test = true)
    {
        $this->id = $id;
        $this->secretKey = $secretKey;
        $this->test = $test;
    }

    /**
     * Initialize amadeuse token in session
     *
     * @return bool
     */
    public function init():bool
    {
        $session = new Session();
        if (! $session->isStarted()) {
            $session->start();
        }
        if (!$session->has('amadeuse_token')) {
            $client = new Client(['verify' => false]);
            try {
                $http = $client->post('https://' . ($this->test ? 'test' : null) . '.travel.api.amadeus.com/v1/security/oauth2/token', [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'body' => 'grant_type=client_credentials&client_id=' . $this->id . '&client_secret=' . $this->secretKey
                ]);
                $response = $http->getBody()
                                 ->getContents();
                $response = json_decode($response, true);
                $session->set('amadeuse_token', $response['token_type'] . ' ' . $response['access_token']);
                $uuid = Uuid::uuid4()
                             ->toString();
                $session->set('amadeuse_uuid', $uuid);
                return true;
            } catch(Exception $e) {
                throw new Exception("You have error in client_credentials or client_secret\nError details: " . $e->getMessage());
            }
        }
    }

    /**
     * Get amadeuse token from session
     *
     * @return string|null
     */
    public static function getToken():string|null
    {
        $session = new Session();
        return $session->get('amadeuse_token') ?? null;
    }

    /**
     * Get amadeuse uuid from session for testing case
     *
     * @return string|null
     */
    public static function getUUID():string|null
    {
        $session = new Session();
        return $session->get('amadeuse_uuid') ?? null;
    }
}
