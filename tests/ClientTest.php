<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Tests;

use GuzzleHttp\Psr7\Utils;
use Http\Mock\Client as HttpMockClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Swis\JsonApi\Client\Client;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_get_and_set_the_base_url()
    {
        $client = new Client;

        $this->assertEquals('', $client->getBaseUri());
        $client->setBaseUri('http://www.test-changed.com');
        $this->assertEquals('http://www.test-changed.com', $client->getBaseUri());
    }

    /**
     * @test
     */
    public function it_can_get_and_set_the_default_headers()
    {
        $client = new Client;

        $this->assertEquals(
            [
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
            ],
            $client->getDefaultHeaders()
        );
        $client->setDefaultHeaders(
            [
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
                'X-Foo' => 'bar',
            ]
        );
        $this->assertEquals(
            [
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
                'X-Foo' => 'bar',
            ],
            $client->getDefaultHeaders()
        );
    }

    /**
     * @test
     */
    public function it_builds_a_get_request()
    {
        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);

        $endpoint = '/test/1';

        $response = $client->get($endpoint, ['X-Foo' => 'bar']);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('GET', $httpClient->getLastRequest()->getMethod());
        $this->assertEquals($endpoint, $httpClient->getLastRequest()->getUri());
        $this->assertEquals(
            [
                'Accept' => ['application/vnd.api+json'],
                'Content-Type' => ['application/vnd.api+json'],
                'X-Foo' => ['bar'],
            ],
            $httpClient->getLastRequest()->getHeaders()
        );
    }

    /**
     * @test
     */
    public function it_builds_a_delete_request()
    {
        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);

        $endpoint = '/test/1';

        $response = $client->delete($endpoint, ['X-Foo' => 'bar']);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('DELETE', $httpClient->getLastRequest()->getMethod());
        $this->assertEquals($endpoint, $httpClient->getLastRequest()->getUri());
        $this->assertEquals(
            [
                'Accept' => ['application/vnd.api+json'],
                'Content-Type' => ['application/vnd.api+json'],
                'X-Foo' => ['bar'],
            ],
            $httpClient->getLastRequest()->getHeaders()
        );
    }

    /**
     * @test
     */
    public function it_builds_a_patch_request()
    {
        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);

        $endpoint = '/test/1';

        $response = $client->patch($endpoint, 'testvar=testvalue', ['Content-Type' => 'application/pdf', 'X-Foo' => 'bar']);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('PATCH', $httpClient->getLastRequest()->getMethod());
        $this->assertEquals('testvar=testvalue', (string) $httpClient->getLastRequest()->getBody());
        $this->assertEquals($endpoint, $httpClient->getLastRequest()->getUri());
        $this->assertEquals(
            [
                'Accept' => ['application/vnd.api+json'],
                'Content-Type' => ['application/pdf'],
                'X-Foo' => ['bar'],
            ],
            $httpClient->getLastRequest()->getHeaders()
        );
    }

    /**
     * @test
     */
    public function it_builds_a_post_request()
    {
        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);

        $endpoint = '/test/1';

        $response = $client->post($endpoint, 'testvar=testvalue', ['Content-Type' => 'application/pdf', 'X-Foo' => 'bar']);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('POST', $httpClient->getLastRequest()->getMethod());
        $this->assertEquals('testvar=testvalue', (string) $httpClient->getLastRequest()->getBody());
        $this->assertEquals($endpoint, $httpClient->getLastRequest()->getUri());
        $this->assertEquals(
            [
                'Accept' => ['application/vnd.api+json'],
                'Content-Type' => ['application/pdf'],
                'X-Foo' => ['bar'],
            ],
            $httpClient->getLastRequest()->getHeaders()
        );
    }

    /**
     * @test
     */
    public function it_builds_other_requests()
    {
        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);

        $endpoint = '/test/1';

        $response = $client->request('OPTIONS', $endpoint, null, ['Content-Type' => 'application/pdf', 'X-Foo' => 'bar']);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('OPTIONS', $httpClient->getLastRequest()->getMethod());
        $this->assertEquals($endpoint, $httpClient->getLastRequest()->getUri());
        $this->assertEquals(
            [
                'Accept' => ['application/vnd.api+json'],
                'Content-Type' => ['application/pdf'],
                'X-Foo' => ['bar'],
            ],
            $httpClient->getLastRequest()->getHeaders()
        );
    }

    /**
     * @test
     */
    public function it_builds_requests_with_a_string_as_body()
    {
        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);

        $body = 'testvar=testvalue';

        $response = $client->request('POST', '/test/1', $body);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('testvar=testvalue', (string) $httpClient->getLastRequest()->getBody());
    }

    /**
     * @test
     */
    public function it_builds_requests_with_a_resource_as_body()
    {
        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);

        $body = fopen('php://temp', 'r+');
        fwrite($body, 'testvar=testvalue');

        $response = $client->request('POST', '/test/1', $body);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('testvar=testvalue', (string) $httpClient->getLastRequest()->getBody());
    }

    /**
     * @test
     */
    public function it_builds_requests_with_a_stream_as_body()
    {
        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);

        $body = Utils::streamFor('testvar=testvalue');

        $response = $client->request('POST', '/test/1', $body);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('testvar=testvalue', (string) $httpClient->getLastRequest()->getBody());
    }

    /**
     * @test
     */
    public function it_builds_requests_without_a_body()
    {
        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);

        $body = null;

        $response = $client->request('POST', '/test/1', $body);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('', (string) $httpClient->getLastRequest()->getBody());
    }

    /**
     * @test
     */
    public function it_prepends_the_base_uri_if_the_endpoint_is_relative()
    {
        $baseUri = 'http://example.com/api';
        $endpoint = '/test/1';

        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);
        $client->setBaseUri($baseUri);

        $client->get($endpoint);

        $this->assertEquals($baseUri.$endpoint, $httpClient->getLastRequest()->getUri());
    }

    /**
     * @test
     */
    public function it_does_not_prepend_the_base_uri_if_the_endpoint_is_already_absolute()
    {
        $baseUri = 'http://example.com/api';
        $endpoint = 'http://foo.bar/test/1';

        $httpClient = new HttpMockClient;
        $client = new Client($httpClient);
        $client->setBaseUri($baseUri);

        $client->get($endpoint);

        $this->assertEquals($endpoint, $httpClient->getLastRequest()->getUri());
    }
}
