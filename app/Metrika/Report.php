<?php

namespace App\Metrika;


class Report
{
    private string $oauth_token;
    private string $id;
    private Metrika $metrika;

    public function __construct($oauth_token, $id, Metrika $metrika)
    {
        $this->oauth_token = $oauth_token;
        $this->id = $id;
        $this->metrika = $metrika;
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

        $method = "/stat/v1/data/bytime";
        $result = $this->metrika->execute("GET", $method, $options);

        return $result;
    }

    public function getStatByData($options)
    {

        $method = "/stat/v1/data";
        $result = $this->metrika->execute("GET", $method, $options);

        return $result;
    }


}
