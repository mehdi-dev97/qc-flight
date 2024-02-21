# QC Flight - Take control of your flight data

## Description :

Tired of sifting through endless flight websites and struggling with convoluted interfaces? This php package empowers developers to easily interact with the Amadeus Flight API, unlocking a world of possibilities for travel applications and services.

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

Example of data

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
Example of return flight data

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

## Flight offer price

Provides initial flight recommendations with basic fare details, including included check-in baggage allowance.

Shows prices for first additional checked bag (if applicable).

Flight Offers Price provides accurate, real-time pricing for your chosen flight with extra baggage.

```php
// Obtain all the quantities offered by the flight selected by the customer or user.
$flightBag = new FlightBags();

foreach ($flightBag->quantities() as $bag) {
  echo $bag->qt; // quantity
  echo $bag->price; // price of quantity offer
  echo $bag->travelerIds; // travalers included in bags
}

```

To save the quantities selected by passengers, you need to use the method ```$flightBags->save($travelersQt)```.

The method takes an array argument of type key value as parameter, the key must be the passenger id and the value the quantity selected.

```php
$flightBag = new FlightBags();

$travelersQt = [
  '1' => 1,
  '2' => 3
];

$flightBag->save($travelersQt);
```

If all goes well, the flight offer will be modified to be compatible with the baggage quantities selected in the price array. You'll see that a new element in the price table has been added, as well as the quantity added to travelerPricings.

Example

```php
  [
    "segmentId" => "8"
    "cabin" => "ECONOMY"
    "fareBasis" => "TL0RAF0A"
    "class" => "T"
    "includedCheckedBags" => array [
      "quantity" => 2
    ]
  ]
```

## Flight Orders

For this step you need to create an order before do ticketing, and to do this you need to use ```FlightOrder::class```.

```php
/**
 * to create flight order.
 *
 * @param array $travelers
 * @param array $contacts
 */

// Note : $travelers and $contacts is required only for set order after you can use FlightOrder in ticketing or get order.

// First argument contain travelers information you need to create a new order.
$travelers = [
  0 => array:6 [▼
    "dateOfBirth" => "1958-02-06"
    "name" => array:2 [▼
      "firstName" => "Jone"
      "lastName" => "Doe"
    ]
    "gender" => "MALE",
    "documents" => array:10 [▼
      "documentType" => "passport"
      "birthPlace" => "FR"
      "issuanceLocation" => "FR"
      "issuanceDate" => "2019-02-02"
      "number" => "DRF3221"
      "expiryDate" => "2027-02-20"
      "issuanceCountry" => "FR"
      "validityCountry" => "FR"
      "nationality" => "FR"
      "holder" => true
    ],
  ]
];

// contacts argument contains the person responsible for the flight order or payment.
$contacts = [
  "addresseeName" => array:2 [▼
    "firstName" => "Jone"
    "lastName" => "Jone"
  ]
  "companyName" => "XXXX"
  "purpose" => "STANDARD"
  "phones" => array:1 [▼
    0 => array:3 [
      "deviceType" => "MOBILE"
      "countryCallingCode" => "33"
      "number" => "0929200032"
    ]
  ]
  "emailAddress" => "xxxxx@gamail.com"
  "address" => array:4 [▼
    "lines" => array:1 [▼
      0 => "xxxxxxxx"
    ]
    "postalCode" => "3232"
    "cityName" => "Marrakech"
    "countryCode" => "MA"
  ]
];

$order = new FlightOrder($travelers, $contacts);

// use set method to send request in amadeuse api to create order.
$order->set();
```

Example of order id

```php
"eJzTd9sfsdew8LYsdsdss9Ak0%3D"
```

Order id use it in ticketing request to confirm the flight.

```php
$order->ticketing();
```
ticketing method return array reponse contain all info about flight order.

```php
"data" => array:10 [▼
      "type" => "flight-order"
      "id" => "eJzTd9sfsdew8LYsdsdss9Ak0%3D"
      "queuingOfficeId" => "PARTQ2692"
      "associatedRecords" => array:2 [▶]
      "flightOffers" => array:1 [▶]
      "travelers" => []
      "contacts" => [...]
      "tickets" => array:1 [▼
        0 => array:5 [▼
          "documentType" => "ETICKET"
          "documentNumber" => "390-9238463936"
          "documentStatus" => "ISSUED"
          "travelerId" => "1"
        ]
      ]
    ]
```

If you need to get informations about order you can use get method to show all data.

```php
$order = new FlightOrder();

$order->reference = "DSE673";

$order->get();
```
## Contributors :

Mehdi Ait Mouh <mehdi.aitmouh.dev@gmail.com> (software engineer)

Ibtissam Toujni <Btissamtoujni@gmail.com> (software engineer and tester)
