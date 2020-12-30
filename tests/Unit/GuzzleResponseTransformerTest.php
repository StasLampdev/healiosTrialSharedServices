<?php

namespace HealiosTrial\Tests;

use GuzzleHttp\Psr7\Response;
use HealiosTrial\Services\GuzzleResponseTransformer;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class GuzzleResponseTransformerTest extends TestCase
{
    /** @var string */
    private $responseBody = '';

    /** @var Response|MockInterface */
    private $response;

    public function setUp(): void
    {
        parent::setUp();
        $this->response = $this->mockResponse();
    }

    public function testToArray(): void
    {
        $expectedData = ['foo' => 'bar'];
        $this->responseBody = json_encode($expectedData);
        $result = GuzzleResponseTransformer::toArray($this->response);
        $this->assertEquals($expectedData, $result);
    }

    public function testToIfInvalidJson(): void
    {
        $expectedData = [];
        $this->responseBody = 'Some Text';
        $result = GuzzleResponseTransformer::toArray($this->response);
        $this->assertEquals($expectedData, $result);
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

        return $response;
    }
}
