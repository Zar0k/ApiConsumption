<?php
declare(strict_types=1);
namespace App\Tests\Client;
use App\Client\GuzzleClient;
use App\Exception\ClientException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ServerException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
final class GuzzleClientTest extends TestCase
{
    const TEST_BASE_PATH = 'https://api.com/';
    const TEST_SSL_CERT = 'test.crt';
    const TEST_SSL_KEY = 'testkey';
    const TEST_URI = 'testuri';

    /**
     * @var ClientInterface|MockObject
     */
    private $guzzle;
    /**
     * @var GuzzleClient
     */
    private $client;

    protected function setUp(): void
    {
        $this->guzzle = $this->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->client = new GuzzleClient(
            $this->guzzle
        );
    }
    /**
     * @covers GuzzleClient::request
     */
    public function testRequest(): void
    {
        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->guzzle
            ->expects($this->once())
            ->method('request')
            ->with('GET', self::TEST_BASE_PATH.self::TEST_URI)
            ->willReturn($response);

        $result = $this->client->request('GET', self::TEST_BASE_PATH.self::TEST_URI);
        $this->assertSame($response, $result);
    }

    /**
     * @covers GuzzleClient::request
     */
    public function testRequestWithClientException(): void
    {
        $request = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response
            ->method('getStatusCode')
            ->willReturn(500);

        $this->guzzle
            ->expects($this->once())
            ->method('request')
            ->with('GET', self::TEST_BASE_PATH.self::TEST_URI)
            ->willThrowException(new ServerException('Internal server error', $request, $response));
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Internal server error');
        $this->expectExceptionCode(500);
        $this->client->request('GET', self::TEST_BASE_PATH.self::TEST_URI);
    }
}
