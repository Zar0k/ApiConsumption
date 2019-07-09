<?php

namespace App;


use App\Api\RestCountriesApi;
use App\Client\GuzzleClient;
use App\Factory\CountryFactory;
use App\Validator\ArrayPropertiesValidator;
use GuzzleHttp\Client;

require __DIR__ . '/vendor/autoload.php';


$client = new GuzzleClient(new Client());
$countryFactory = new CountryFactory(new ArrayPropertiesValidator());
$restCountriesApi = new RestCountriesApi($client, $countryFactory );


if ($argc > 2) {
    $countryA = $restCountriesApi->getCountryByName($argv[1]);
    $countriesA = $restCountriesApi->getCountriesByIsoCode($countryA->getCountryIso639());

    $countryB = $restCountriesApi->getCountryByName($argv[2]);
    $matches = 0;

    foreach ($countriesA as $country) {
        if ($countryB->getName() == $country->getName()) {
            $matches ++;
        }
    }

    if ($matches >= 1) {
        echo $countryA->getName() . " and " . $countryB->getName() . " speaks the same language";
    } else {
        echo $countryA->getName() . " and " . $countryB->getName() . " do not speak the same language";
    }

} else {
    $countryObj = $restCountriesApi->getCountryByName($argv[1]);
    $countries = $restCountriesApi->getCountriesByIsoCode($countryObj->getCountryIso639());
    echo "Country language code: " . $countryObj->getCountryIso() . " 
" . $countryObj->getName() . " speaks same language with these countries: ";
    foreach ($countries as $country) {
        if ($countryObj->getName() != $country->getName()) {
          echo $country->getName() . ', ';
        }
    }
}
