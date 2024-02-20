# QC Flight - Take control of your flight data

## Description :

Tired of sifting through endless flight websites and struggling with convoluted interfaces? This Python module empowers developers to easily interact with the Amadeus Flight API, unlocking a world of possibilities for travel applications and services.

[Amadeuse Developer Documentation](https://developers.amadeus.com)

## INSTALLATION:

Install the package through [Composer](http://getcomposer.org/).

```composer require "bl/qc-flight"```

## Quick Usage Example

```php
// Quick Usage of amadeuse initializer and get flight offer.
    
$amadeuse = new Amadeuse('YOUR_AMADEUSE_ID', 'YOUR_AMADEUSE_SECRET_KEY');

$amadeuse->init();

$adults = 2;

$childrens = 1;

$infant = 0;

$flightOffer = new FlightOffer('CMN', 'CDG', ['2024-01-20', '2024-01-24'], 'USD', 'ECONOMY', $adults, $childrens, $infant);

$offers = $flightOffer->getAll();

foreach ($offers->fetchObject() as $flight) {

    echo $flight->id;

    echo $flight->type;

    echo $flight->source;

    $itineraries = $flight->itineraries;

}

// FOR FULL USAGE, SEE BELOW..
```

## Usage

### IMPORTANT NOTE!

By default, token and UUID are stored in session during creation of token of creation order.

### Initialization

``` php
/**
 * Create new instance of amadeuse quick connect
 *
 * @param string $id
 * @param string $secretKey
 * @param bool $test
 */
$amadeuse = new Amadeuse('YOUR_AMADEUSE_ID', 'YOUR_AMADEUSE_SECRET_KEY', false);

// Initialize Amadeuse configuration.
$amadeuse->init();

// To get amadeuse token created in init.
echo $amadeuse::getToken();

// Get UUID by utilizing the test case phase.
echo $amadeuse::getUUID();
```

Example

``` php
// Bearer token returned by getToken.
"Bearer GoH3ZWKSCitA0AzuHMDZeIAJcV82"

// UUID returned by getUUID.
"4e98382f-63c1-4c53-84ff-cc0609d52545"
```

## Flight offers

Every API call returns a Response object. If the API call contained a JSON response it will parse to array or object.

```php
// Quick Usage of amadeus initializer and gets a flight offer.
    
$amadeuse = new Amadeuse('YOUR_AMADEUSE_ID', 'YOUR_AMADEUSE_SECRET_KEY');

$amadeuse->init();

/**
 * Create a new instance of flight offer and set all search params
 *
 * @param string|array $origins
 * @param string|array $destinations
 * @param array $dates
 * @param string $currency
 * @param string $cabin
 * @param int $adults
 * @param int $childrens
 * @param int $infants
 */
$flightOffer = new FlightOffer('CMN', 'CDG', ['2024-01-20', '2024-01-24'], 'USD', 'ECONOMY', 1, 0, 0);

// Send a request to get all flights offers.
$offers = $flightOffer->getAll();

// Get data represented in array format. 
$arrayData = $offers->fetchArray();

// Get data represented in object format.
$objectData = $offers->fetchObject();
```
Example

``` php
// fetched data by fetchObject

{
    type: "flight-offer"
    id: "1"
    source: "GDS"
    instantTicketingRequired: false
    nonHomogeneous: false
    oneWay: false
    lastTicketingDate: "2024-02-28"
    lastTicketingDateTime: "2024-02-28"
    numberOfBookableSeats: 9
    itineraries: array:2 [...]
    price: {
      currency: "USD"
      total: "358.40"
      base: "156.00"
      fees: array:2 [
        0 => {
          amount: "0.00"
          type: "SUPPLIER"
        }
        1 => {
          amount: "0.00"
          type: "TICKETING"
        }
      ]
      grandTotal: "358.40"
    }
    pricingOptions: {
      fareType: array:1 [
        0 => "PUBLISHED"
      ]
      includedCheckedBagsOnly: true
    }
    validatingAirlineCodes: array:1 [
      0 => "AT"
    ]
    travelerPricings: array:1 [...]
    fareRules: {
      rules: array:3 [...]
    }
}

// Fetched array data by fetchArray

[
    "type" => "flight-offer"
    "id" => "1"
    "source" => "GDS"
    "instantTicketingRequired" => false
    "nonHomogeneous" => false
    "oneWay" => false
    "lastTicketingDate" => "2024-02-28"
    "lastTicketingDateTime" => "2024-02-28"
    "numberOfBookableSeats" => 9
    "itineraries" => array:2 [...]
    "price" => array:5 [
      "currency" => "USD"
      "total" => "358.40"
      "base" => "156.00"
      "fees" => array:2 [
        0 => array:2 [
          "amount" => "0.00"
          "type" => "SUPPLIER"
        ]
        1 => array:2 [
          "amount" => "0.00"
          "type" => "TICKETING"
        ]
      ]
      "grandTotal" => "358.40"
    ]
    "pricingOptions" => array:2 [
      "fareType" => array:1 [
        0 => "PUBLISHED"
      ]
      "includedCheckedBagsOnly" => true
    ]
    "validatingAirlineCodes" => array:1 [
      0 => "AT"
    ]
    "travelerPricings" => array:1 [...]
    "fareRules" => array:1 [
      "rules" => array:3 [...]
    ]
]
```
To save flight selected by user we have two static methods, one for save and other to get saved offer.

```php

$id = 1; // flight offer id

/**
 * Set selected flight in offer list.
 *
 * @param int $childrens
 * @param int $infants
 * 
 * @return bool
 */
FlightOffer::set($id);

/* Note: The method returns true if offer exists in search list by using the id.
/* you can find the id of flight inside offers : "id" => "1"
*/

/**
 * Get saved or selected offer by the user.
 * 
 * @return array|null
 */
var_dump(FlightOffer::get());
```


## Contributors :

Mehdi Ait Mouh <mehdi.aitmouh.dev@gmail.com> (Create logic of software)

Ibtissam Toujni <Btissamtoujni@gmail.com> (Software tester)
