<?php

namespace Vicomm;

use DateTime;
use Vicomm\Exceptions\RequestException;
use Vicomm\Resources\Resource;

class Vicomm
{
    /**
     * Vicomm application code
     * @var string
     */
    private static $code;

    /**
     * Vicomm application key
     * @var string
     */
    private static $apiKey;

    /**
     * Vicomm environment
     * @var string
     */
    private static $production = false;

    /**
     * Set a credentials and environment for Vicomm API
     * @return void
     * @throws \Exception
     */
    public static function init(string $code, string $apiKey, bool $production = false)
    {
        self::$code = $code;
        self::$apiKey = $apiKey;
        self::$production = $production;
    }

    /**
     * Generate string of authenticate
     * @return string
     * @throws \Exception
     */
    public static function auth(): string
    {
        if (empty(self::$code) || empty(self::$apiKey)) {
            throw new RequestException("Missing Vicomm API key or code, ensure that execute init method.");
        }

        $now = (string)(new DateTime)->getTimestamp();

        $uniqToken = implode('', [
            self::$apiKey,
            $now
        ]);

        $uniqTokenHash = hash('sha256', $uniqToken);

        return base64_encode(implode(';', [
            self::$code,
            $now,
            $uniqTokenHash
        ]));
    }

    /**
     * Make a new instance on resource requested
     * @param string $name
     * @param array $arguments
     * @return Resource New instance of vicomm api resource
     * @throws Exceptions\RequestException
     */
    public static function __callStatic(string $name, array $arguments): Resource
    {
        if (!key_exists($name, Settings::API_RESOURCES)) {
            throw new RequestException("Undefined resource {$name} to access.");
        }

        $resourceClass = Settings::API_RESOURCES[$name]['class'];
        $apiType = Settings::API_RESOURCES[$name]['api'];

        return new $resourceClass(new Requestor(Settings::BASE_URL[$apiType], self::$production, self::auth()));
    }
}