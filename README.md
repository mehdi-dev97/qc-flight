# QC Flight - Take control of your flight data

## Description :
Tired of sifting through endless flight websites and struggling with convoluted interfaces? This Python module empowers developers to easily interact with the Amadeus Flight API, unlocking a world of possibilities for travel applications and services.

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

$flightOffer = new FlightOffer('CMN', 'CDG', ['2024-02-20', '2024-02-24'], 'USD', $adults, $childrens, $infant);

foreach ($flightOffer->getAll() as $flight) {

    echo $flight->id;

    echo $flight->itiniraries;

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
 * @param string|array $id
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

## Contributors :

Mehdi Ait Mouh <mehdi.aitmouh.dev@gmail.com> (Create logic of software)

Ibtissam Toujni <Btissamtoujni@gmail.com> (Software tester)
