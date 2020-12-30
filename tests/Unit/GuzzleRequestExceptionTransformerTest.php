<?php

namespace HealiosTrial\Tests;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HealiosTrial\Services\GuzzleRequestExceptionTransformer;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class GuzzleRequestExceptionTransformerTest extends TestCase
{
    /** @var string */
    private $message = '';

    /** @var string */
    private $responseBody = '';

    public function testToStringIfErrors(): void
    {
        $error = 'Invalid data';
        $this->responseBody = json_encode(['errors' => $error]);
        $guzzleException = $this->createException();
        $result = GuzzleRequestExceptionTransformer::toString($guzzleException);
        $this->assertEquals($error, $result);
    }

    public function testToStringIfMessage(): void
    {
        $message = 'Some message';
        $this->responseBody = json_encode(['message' => $message]);
        $guzzleException = $this->createException();
        $result = GuzzleRequestExceptionTransformer::toString($guzzleException);
        $this->assertEquals($message, $result);
    }

    public function testToStringIfInvalidJson(): void
    {
        $this->message = 'Exception message';
        $this->responseBody = 'Some text';
        $guzzleException = $this->createException();
        $result = GuzzleRequestExceptionTransformer::toString($guzzleException);
        $this->assertEquals($this->message, $result);
    }

    /**
     * @return RequestException|MockInterface
     */
    private function createException()
    {
        return new RequestException($this->message, new Request('GET', ''), $this->mockResponse());
    }

    /**
     * @return Response|MockInterface
     */
    private function mockResponse()
    {
        /** @var Response|MockInterface $response */
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->andReturnUsing(function () {
            return $this->responseBody;
        });
        $response->shouldReceive('getStatusCode')->andReturnUsing(function () {
            return SymfonyResponse::HTTP_OK;
        });

        return $response;
    }
}
