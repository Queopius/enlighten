<?php

namespace Tests\Unit\Models;

use Styde\Enlighten\Models\HttpData;
use Tests\TestCase;

class HideRequestHeadersTest extends TestCase
{
    /** @test */
    function hides_request_headers()
    {
        $httpData = new HttpData([
            'request_headers' => [
                'host' => 'localhost',
                'accept' => ['application/json'],
                'user-agent' => ['Mozilla'],
            ],
        ]);

        config([
            'enlighten.request.headers' => [
                'hide' => [
                    'host',
                ],
            ],
        ]);

        $this->assertSame([
            'accept' => ['application/json'],
            'user-agent' => ['Mozilla'],
        ], $httpData->request_headers);
    }

    /** @test */
    function can_overwrite_headers()
    {
        $httpData = new HttpData([
            'request_headers' => [
                'host' => ['original.host'],
                'accept' => ['application/json'],
            ],
        ]);

        config([
            'enlighten.request.headers' => [
                'overwrite' => [
                    'host' => ['overwritten.host'],
                ],
            ],
        ]);

        $this->assertSame([
            'host' => ['overwritten.host'],
            'accept' => ['application/json'],
        ], $httpData->request_headers);
    }

    /** @test */
    function hidden_headers_take_precedence_over_overwritten_headers()
    {
        $httpData = new HttpData([
            'request_headers' => [
                'host' => 'localhost',
                'accept' => ['application/json'],
            ],
        ]);

        config([
            'enlighten.request.headers' => [
                'hide' => [
                    'host',
                ],
                'overwrite' => [
                    'host' => ['overwritten.host'],
                ],
            ],
        ]);

        $this->assertSame([
            'accept' => ['application/json'],
        ], $httpData->request_headers);
    }
}
