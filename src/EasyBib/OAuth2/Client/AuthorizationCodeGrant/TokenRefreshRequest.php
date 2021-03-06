<?php

namespace EasyBib\OAuth2\Client\AuthorizationCodeGrant;

use EasyBib\OAuth2\Client\TokenResponse\TokenResponse;
use Guzzle\Http\ClientInterface;

class TokenRefreshRequest
{
    const GRANT_TYPE = 'refresh_token';

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var ServerConfig
     */
    private $serverConfig;

    /**
     * @var \Guzzle\Http\ClientInterface
     */
    private $httpClient;

    /**
     * @param string $refreshToken
     * @param ServerConfig $serverConfig
     * @param ClientInterface $httpClient
     */
    public function __construct(
        $refreshToken,
        ServerConfig $serverConfig,
        ClientInterface $httpClient
    ) {
        $this->refreshToken = $refreshToken;
        $this->serverConfig = $serverConfig;
        $this->httpClient = $httpClient;
    }

    /**
     * @return TokenResponse
     */
    public function send()
    {
        $url = $this->serverConfig->getParams()['token_endpoint'];

        $params = [
            'grant_type' => self::GRANT_TYPE,
            'refresh_token' => $this->refreshToken,
        ];

        $request = $this->httpClient->post($url, [], $params);
        $response = $request->send();

        return new TokenResponse($response);
    }
}
