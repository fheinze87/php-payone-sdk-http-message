<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Http\Message;

use Cakasim\Payone\Sdk\Http\Factory\RequestFactory;
use Cakasim\Payone\Sdk\Http\Factory\ServerRequestFactory;
use Cakasim\Payone\Sdk\Http\Factory\StreamFactory;
use Cakasim\Payone\Sdk\Http\Factory\UriFactory;
use Cakasim\Payone\Sdk\Http\Message\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ServerRequestTest extends TestCase
{
    /**
     * @var UriFactory
     */
    protected $uriFactory;

    /**
     * @var StreamFactory
     */
    protected $streamFactory;

    /**
     * @var ServerRequestFactory
     */
    protected $serverRequestFactory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->serverRequestFactory = new ServerRequestFactory(
            $this->uriFactory = new UriFactory(),
            $this->streamFactory = new StreamFactory()
        );
    }

    /**
     * Checks whether ServerRequest is PSR-7 implementation.
     */
    public function testIsPsr7ServerRequest(): void
    {
        $request = $this->serverRequestFactory->createServerRequest('GET', '/index.php', $_SERVER);
        $this->assertInstanceOf(ServerRequestInterface::class, $request);
    }

    public function testGlobalHeaderParsing(): void
    {
        $request = $this->serverRequestFactory->createServerRequest('GET', '/index.php', [
            'SERVER_PROTOCOL' => 'HTTP/1.0',
            'HTTP_CONTENT_TYPE' => 'application/json',
            'HTTP_X_CUSTOM_HEADER' => 'Custom Header',
        ]);

        $this->assertEquals('1.0', $request->getProtocolVersion());
        $this->assertEquals(['application/json'], $request->getHeader('Content-Type'));
        $this->assertEquals(['Custom Header'], $request->getHeader('X-Custom-Header'));
    }
}
