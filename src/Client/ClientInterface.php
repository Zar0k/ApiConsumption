<?php
declare(strict_types=1);
namespace App\Client;
use App\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
interface ClientInterface
{
    /**
     * @param string $method
     * @param string $uri
     *
     * @throws ClientException
     *
     * @return ResponseInterface
     */
    public function request(string $method, string $uri): ResponseInterface;
}