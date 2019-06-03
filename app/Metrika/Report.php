<?php

namespace App\Metrika;


class Report
{
    private $oauth_token;
    private $id;

    public function __construct($oauth_token, $id)
    {
        $this->oauth_token = $oauth_token;
        $this->id = $id;
    }

    public function getToken()
    {
        return $this->oauth_token;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStatByTime($options)
    {
        $token = $this->getToken();

        $method = "/stat/v1/data/bytime";
        $metrika = new Metrika($token);
        $result = $metrika->execute("GET", $method, $options);

        return $result;
    }

    public function getStatByData($options)
    {
        $token = $this->getToken();

        $method = "/stat/v1/data";
        $metrika = new Metrika($token);
        $result = $metrika->execute("GET", $method, $options);

        return $result;
    }


}