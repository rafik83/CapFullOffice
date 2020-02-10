<?php

namespace CPA\OAuthServerBundle\Service;

use OAuth2;

class OAuth2Client {

    //put your code here
    protected $client;
    protected $authEndpoint;
    protected $tokenEndpoint;
    protected $redirectUrl;
    protected $grant;
    protected $params;

    public function __construct(OAuth2\Client $client, $authEndpoint, $tokenEndpoint, $redirectUrl, $grant, $params) {
        $this->client = $client;
        $this->authEndpoint = $authEndpoint;
        $this->tokenEndpoint = $tokenEndpoint;
        $this->redirectUrl = $redirectUrl;
        $this->grant = $grant;
        $this->params = $params;
//        dump($client);
//         die('__construct + OAuth2Client');
    }

    public function getAuthenticationUrl() {

        die('getAuthenticationUrl');
        return $this->client->getAuthenticationUrl($this->authEndpoint, $this->redirectUrl);
    }

    public function getAccessToken($code = null) {
        dump($this->params);
//         dump($code);
        die('getAccessToken');
        if ($code !== null) {
            $this->params['code'] = $code;
        }

        $response = $this->client->getAccessToken($this->tokenEndpoint, $this->grant, $this->params);
//        var_dump($response);
//        die('$response');
        if (isset($response['result']) && isset($response['result']['access_token'])) {
            $accessToken = $response['result']['access_token'];
//            var_dump($accessToken);
//            die('$accessToken');
            $this->client->setAccessToken($accessToken);
//            var_dump($this->client);
//            die('$accessToken');
            return $accessToken;
        }

        throw new OAuth2\Exception(sprintf('Unable to obtain Access Token. Response from the Server: %s ', var_export($response)));
    }

    public function fetch($url) {
        die('fetch');
        return $this->client->fetch($url);
    }

}
