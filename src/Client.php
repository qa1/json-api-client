<?php

namespace Swis\JsonApi\Client;

use Http\Client\Exception\HttpException;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Swis\JsonApi\Client\Interfaces\ClientInterface;

class Client implements ClientInterface
{
    /**
     * @var string
     */
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    const METHOD_GET = 'GET';

    /**
     * @var string
     */
    const METHOD_PATCH = 'PATCH';

    /**
     * @var string
     */
    const METHOD_POST = 'POST';

    /**
     * @var \Http\Client\HttpClient
     */
    private $client;

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    protected $defaultHeaders = [
        'Accept'       => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ];

    /**
     * @param \Http\Client\HttpClient $client
     * @param string                  $baseUri
     * @param MessageFactory          $messageFactory
     */
    public function __construct(
        HttpClient $client,
        string $baseUri,
        MessageFactory $messageFactory
    ) {
        $this->client = $client;
        $this->baseUri = $baseUri;
        $this->messageFactory = $messageFactory;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * @param string $baseUri
     */
    public function setBaseUri(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @param string $endpoint
     * @param array  $headers
     *
     * @throws \Http\Client\Exception
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(string $endpoint, array $headers = []): ResponseInterface
    {
        return $this->request(static::METHOD_GET, $endpoint, null, $headers);
    }

    /**
     * @param string                                                                         $endpoint
     * @param resource|string|int|float|bool|\Psr\Http\Message\StreamInterface|callable|null $body
     * @param array                                                                          $headers
     *
     * @throws \Http\Client\Exception
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function post(string $endpoint, $body, array $headers = []): ResponseInterface
    {
        return $this->request(static::METHOD_POST, $endpoint, $body, $headers);
    }

    /**
     * @param string                                                                         $endpoint
     * @param resource|string|int|float|bool|\Psr\Http\Message\StreamInterface|callable|null $body
     * @param array                                                                          $headers
     *
     * @throws \Http\Client\Exception
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function patch(string $endpoint, $body, array $headers = []): ResponseInterface
    {
        return $this->request(static::METHOD_PATCH, $endpoint, $body, $headers);
    }

    /**
     * @param string $endpoint
     * @param array  $headers
     *
     * @throws \Http\Client\Exception
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(string $endpoint, array $headers = []): ResponseInterface
    {
        return $this->request(static::METHOD_DELETE, $endpoint, null, $headers);
    }

    /**
     * @param string                                                                         $method
     * @param string                                                                         $endpoint
     * @param resource|string|int|float|bool|\Psr\Http\Message\StreamInterface|callable|null $body
     * @param array                                                                          $headers
     *
     * @throws \Http\Client\Exception
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $endpoint, $body = null, array $headers = []): ResponseInterface
    {
        $request = $this->buildRequest($method, $endpoint, $body, $headers);

        try {
            return $this->client->sendRequest($request);
        } catch (HttpException $e) {
            return $e->getResponse();
        }
    }

    /**
     * @param string                                                                         $method
     * @param string                                                                         $endpoint
     * @param resource|string|int|float|bool|\Psr\Http\Message\StreamInterface|callable|null $body
     * @param array                                                                          $headers
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function buildRequest(string $method, string $endpoint, $body = null, array $headers = []): RequestInterface
    {
        return $this->messageFactory->createRequest($method, $this->getEndpoint($endpoint), $this->mergeHeaders($headers), $body);
    }

    /**
     * @param string $endpoint
     *
     * @return string
     */
    protected function getEndpoint(string $endpoint): string
    {
        return $this->baseUri.$endpoint;
    }

    protected function mergeHeaders(array $headers = []): array
    {
        return array_merge($this->defaultHeaders, $headers);
    }

    public function getDefaultHeaders(): array
    {
        return $this->defaultHeaders;
    }

    public function setDefaultHeaders(array $defaultHeaders)
    {
        $this->defaultHeaders = $defaultHeaders;
    }
}
