<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use SteamAuth\SteamApi;

class SteamApiTest extends PHPUnit\Framework\TestCase
{
    private $apiKey;
    private $httpClient;
    /**
     * @var MockHandler
     */
    private $mock;

    /**
     * @var SteamApi
     */
    private $steamApi;

    protected function setUp(): void
    {
        $this->apiKey = '';
        $this->mock = new MockHandler();
        $this->httpClient = new \GuzzleHttp\Client([
            'handler' => HandlerStack::create($this->mock)
        ]);
        $this->steamApi = new SteamApi($this->apiKey, $this->httpClient);
    }

    public function testGetProfile() {
        $this->mock->append(
            new Response(200, [], json_encode([
                    'response' => [
                        'players' => [
                        ]
                    ]
                ]))
        );
        $this->assertEquals($this->steamApi->getProfile(''), null);
    }

}