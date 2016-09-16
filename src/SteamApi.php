<?php

namespace SteamAuth;

use GuzzleHttp\ClientInterface;

class SteamApi implements SteamApiInterface
{
    protected $apiProfileUrl = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s';
    protected $apiKey = 'Your Steam API key';
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * SteamApi constructor.
     * @param string $apiKey
     * @param ClientInterface $httpClient
     */
    public function __construct($apiKey, ClientInterface $httpClient = null)
    {
        if (!$httpClient)
            $httpClient = new \GuzzleHttp\Client([
                'timeout' => 30,
                'connect_timeout' => 5,
            ]);

        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient;
    }

    public function getProfile($sid)
    {
        return @json_decode(
            $this->httpClient->get(sprintf($this->apiProfileUrl, $this->apiKey, $sid))
                ->getBody(), true)['response']['players']['0'];
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
}