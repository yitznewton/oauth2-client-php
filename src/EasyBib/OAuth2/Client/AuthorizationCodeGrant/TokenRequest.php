<?php

namespace EasyBib\OAuth2\Client\AuthorizationCodeGrant;

use EasyBib\OAuth2\Client\AuthorizationCodeGrant\Authorization\AuthorizationResponse;
use EasyBib\OAuth2\Client\TokenRequestInterface;
use EasyBib\OAuth2\Client\TokenResponse\TokenResponse;
use Guzzle\Http\ClientInterface;

class TokenRequest implements TokenRequestInterface
{
    const GRANT_TYPE = 'authorization_code';

    /**
     * @var ClientConfig
     */
    private $clientConfig;

    /**
     * @var ServerConfig
     */
    private $serverConfig;

    /**
     * @var \Guzzle\Http\ClientInterface
     */
    private $httpClient;

    /**
     * @var AuthorizationResponse
     */
    private $authorizationResponse;

    /**
     * @param ClientConfig $clientConfig
     * @param ServerConfig $serverConfig
     * @param ClientInterface $httpClient
     * @param AuthorizationResponse $authorization
     */
    public function __construct(
        ClientConfig $clientConfig,
        ServerConfig $serverConfig,
        ClientInterface $httpClient,
        AuthorizationResponse $authorization
    ) {
        $this->clientConfig = $clientConfig;
        $this->serverConfig = $serverConfig;
        $this->httpClient = $httpClient;
        $this->authorizationResponse = $authorization;
    }

    /**
     * @return TokenResponse
     */
    public function send()
    {
        $url = $this->serverConfig->getParams()['token_endpoint'];
        $request = $this->httpClient->post($url, [], $this->getParams());
        $response = $request->send();

        return new TokenResponse($response);
    }

    /**
     * @return array
     */
    private function getParams()
    {
        return [
            'grant_type' => self::GRANT_TYPE,
            'code' => $this->authorizationResponse->getCode(),
            'redirect_uri' => $this->clientConfig->getParams()['redirect_uri'],
            'client_id' => $this->clientConfig->getParams()['client_id'],
        ];
    }
}
