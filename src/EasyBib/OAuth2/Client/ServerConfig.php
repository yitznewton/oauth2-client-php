<?php

namespace EasyBib\OAuth2\Client;

class ServerConfig
{
    /**
     * @var array
     */
    private $params;

    private static $validParams = [
        'token_endpoint',
    ];

    public function __construct(array $params)
    {
        self::validate($params);
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    private static function validate(array $params)
    {
        $validator = new ArrayValidator(self::$validParams, self::$validParams);

        if (!$validator->validate($params)) {
            throw new InvalidServerConfigException();
        }
    }
}
