<?php
declare(strict_types=1);
namespace App\Client;
use App\Exception\ClientException;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
class GuzzleClient implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $basePath;

    /**
     * @param GuzzleClientInterface $client
     */
    public function __construct(GuzzleClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Make a HTTP request using the given options and configured client.
     *
     * @param string $method
     * @param string $uri
     * @return ResponseInterface
     */
    public function request(string $method, string $uri): ResponseInterface
    {
        try {
            return $this->client->request($method, $this->basePath.$uri);
        } catch (GuzzleException $e) {
            throw new ClientException($e->getMessage(), $e->getCode());
        }
    }
}