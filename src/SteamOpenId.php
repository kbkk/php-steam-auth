<?php
/**
 * Created by PhpStorm.
 * User: Dziadzia
 * Date: 2016-05-29
 * Time: 20:41
 */

namespace SteamAuth;


use GuzzleHttp\ClientInterface;

class SteamOpenId
{
    protected $config;
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    protected $redirectParams = [
        'openid.ns' => 'http://specs.openid.net/auth/2.0',
        'openid.mode' => 'checkid_setup',
        'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
        'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select'
    ];

    protected $verifyWhitelist = [
        'openid.ns' => 'openid_ns',
        'openid.op_endpoint' => 'openid_op_endpoint',
        'openid.claimed_id' => 'openid_claimed_id',
        'openid.identity' => 'openid_identity',
        'openid.return_to' => 'openid_return_to',
        'openid.response_nonce' => 'openid_response_nonce',
        'openid.assoc_handle' => 'openid_assoc_handle',
        'openid.signed' => 'openid_signed',
        'openid.sig' => 'openid_sig',
    ];

    /**
     * SteamOpenId constructor.
     * @param array $options
     * @param ClientInterface $httpClient
     */
    public function __construct(array $options = [], ClientInterface $httpClient = null)
    {
        if (!$httpClient)
            $httpClient = new \GuzzleHttp\Client([
                'timeout' => 30,
                'connect_timeout' => 5,
            ]);

        $this->httpClient = $httpClient;

        $this->configureDefaults();
        $this->setConfigOptions($options);
    }

    public function getRedirectUrl()
    {
        $params = $this->redirectParams;
        $params['openid.return_to'] = $this->config['return_to'];
        $params['openid.realm'] = $this->config['realm'];

        return $this->config['op_url'] . '?' . http_build_query($params);
    }

    public function verifyAssertion(array $getParams)
    {
        $params = [];
        foreach ($this->verifyWhitelist as $k => $v) {
            if(isset($getParams[$v]))
                $params[$k] = $getParams[$v];
        }

        $params['openid.mode'] = 'check_authentication';

        if (!isset($params['openid.return_to'])
            || $params['openid.return_to'] != $this->config['return_to']
        )
            return false;

        $response = $this->httpClient->post($this->config['op_url'], [
            'form_params' => $params
        ]);

        if (strpos($response->getBody(), 'is_valid:true') === false)
            return false;

        $id = $params['openid.identity'];
        return substr($id, strrpos($id, '/') + 1);
    }

    private function configureDefaults()
    {
        $defaults = [
            'op_url' => 'https://steamcommunity.com/openid/login',
            'return_to' => null,
            'realm' => null,
        ];

        $this->config = $defaults;
    }

    public function getConfig($option = null)
    {
        return $option === null
            ? $this->config
            : (isset($this->config[$option]) ? $this->config[$option] : null);
    }

    public function setConfigOptions(array $options)
    {
        $this->config = array_merge($this->config, $options);
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }


}