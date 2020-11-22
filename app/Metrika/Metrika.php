<?php

namespace App\Metrika;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Metrika
{
    private string $oauth_token;
    private string $url;

    public function __construct($oauth_token)
    {
        $this->oauth_token = $oauth_token;
        $this->url = "https://api-metrika.yandex.ru";
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getToken()
    {
        return $this->oauth_token;
    }

    public function getHttpHeaders()
    {
        return [
            "Content-Type" => "application/x-yametrika+json",
            "Authorization" => "OAuth ".$this->getToken()."",
            "Accept" => "application/x-yametrika+json"
        ];
    }

    public function execute($method = "GET", $path, $options = [])
    {
        $url = $this->getUrl();
        $headers = $this->getHttpHeaders();


        $client = new Client([
           'headers' => $headers,
           'base_uri' => $url
        ]);


        try {
            // TODO: если передали $method POST, то надо использовать form_params
            $response = $client->request($method, $path, [
                'query' => $options
            ]);
            return $response->getBody()->getContents();

        } catch (GuzzleException $e) {

            return response(['error' => $e->getMessage()],200);
        }




    }
}
