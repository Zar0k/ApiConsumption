<?php
declare(strict_types=1);
namespace App\Api;
use App\Client\ClientInterface;
use App\Exception\AccessDeniedException;
use App\Exception\ApiException;
use App\Exception\ClientException;
use App\Exception\DecodingException;
use App\Exception\NotFoundException;
use App\Factory\CountryFactoryInterface;
use App\Model\Country;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class RestCountriesApi
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var CountryFactoryInterface
     */

    private $countryFactory;
    /**
     * @param ClientInterface          $client
     * @param CountryFactoryInterface $countryFactory
     */
    public function __construct(
        ClientInterface $client,
        CountryFactoryInterface $countryFactory
    ) {
        $this->client = $client;
        $this->countryFactory = $countryFactory;
    }
    
    /**
     * Get details of a specific Country.
     *
     * @param string               $name
     *
     * @throws AccessDeniedException if the access token is not valid
     * @throws ApiException for all other fatal API errors
     *
     * @return Country|null
     */
    public function getCountryByName(string $name): ?Country
    {
        $endpoint = 'https://restcountries.eu/rest/v2/name/' . $name . '?fullText=true';
        try {
            $data = $this->request( 'GET', $endpoint);
        } catch (NotFoundException $e) {
            return null;
        } catch (DecodingException $e) {
            throw new ApiException(
                sprintf('Error decoding response: %s', $e->getMessage()),
                ApiException::CODE__PARSE_ERROR
            );
        }
        if ($data === null) {
            throw new ApiException(
                sprintf('Cannot not parse Country from empty response body'),
                ApiException::CODE__PARSE_ERROR
            );
        }
        try {
            $country = $this->countryFactory->createFromArray($data);
        } catch (InvalidArgumentException $e) {
            throw new ApiException(
                'Could not parse Country from response body: ' . $e->getMessage(),
                ApiException::CODE__PARSE_ERROR
            );
        }

        return $country;
    }

    public function getCountriesByIsoCode(string $isoCode): ?array
    {
        $endpoint = 'https://restcountries.eu/rest/v2/lang/' . $isoCode;
        try {
            $data = $this->request( 'GET', $endpoint);
        } catch (NotFoundException $e) {
            return null;
        } catch (DecodingException $e) {
            throw new ApiException(
                sprintf('Error decoding response: %s', $e->getMessage()),
                ApiException::CODE__PARSE_ERROR
            );
        }
        if ($data === null) {
            throw new ApiException(
                sprintf('Cannot not parse Country from empty response body'),
                ApiException::CODE__PARSE_ERROR
            );
        }

        $countries = [];

        try {
            $array = [];
            foreach ($data as $key => $country) {
                $array[0] = $country;
                $countryObject = $this->countryFactory->createFromArray($array);
                array_push($countries, $countryObject);
            }
        } catch (InvalidArgumentException $e) {
            throw new ApiException(
                'Could not parse Country from response body: ' . $e->getMessage(),
                ApiException::CODE__PARSE_ERROR
            );
        }

        return $countries;
    }

    /**
     * Make a request to the client and parse the response body.
     *
     * @param string               $method
     * @param string               $endpoint
     *
     * @throws AccessDeniedException if the access token is not valid
     * @throws NotFoundException if the requested resource could not be found
     * @throws ApiException for all other fatal API errors
     *
     * @return array|null
     */
    private function request(string $method, string $endpoint): ?array
    {
        // Perform the request
        try {
            $response = $this->client->request($method, $endpoint);
        } catch (ClientException $e) {
            switch ($e->getCode()) {
                case 403:
                    throw new AccessDeniedException($e->getMessage(), $e->getCode());
                case 404:
                    throw new NotFoundException($e->getMessage(), $e->getCode());
                default:
                    throw new ApiException($e->getMessage(), $e->getCode());
            }
        }
        return $this->getResponseContents($response);
    }
    /**
     * @param ResponseInterface $response
     *
     * @return array|null
     */
    private function getResponseContents(ResponseInterface $response): ?array
    {
        $rawContents = json_decode($response->getBody()->getContents(), true);
        if (!is_array($rawContents)) {
            return null;
        }

        return $rawContents;
    }
}