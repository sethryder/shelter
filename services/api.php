<?php

class API
{
    private $api;
    private static $hostname;
    private static $username;
    private static $password;
    private static $domain;
    private static $configured = false;

    public function __construct()
    {
        if (!self::$configured)
        {
            throw new ErrorException('You must set the config!');
        }
        self::createAPIClient();
    }

    public static function config($hostname, $username, $password, $domain)
    {
        self::$hostname = $hostname;
        self::$username = $username;
        self::$password = $password;
        self::$domain = $domain;
        self::$configured = true;
    }

    public static function get_domain()
    {
        return self::$domain;
    }

    public function call($target, array $params=null)
    {
        if ($params)
        {
            $params['params'] = $params;
        }

        $result = $this->api->call($target, $params);
        return $result;
    }

    private function createAPIClient()
    {
        $this->api = new API_Client(self::$hostname, self::$username, self::$password);
    }
}