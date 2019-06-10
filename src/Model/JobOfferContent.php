<?php declare(strict_types = 1);

namespace App\Model;

use GuzzleHttp\Client as GuzzleClient;

class JobOfferContent
{
    public function getJobOffers(string $url) : array
    {
        $client = new GuzzleClient();
        $response = $client->get($url);
        $body = $response->getBody();
        return json_decode($body->getContents(),true);
    }

    public function getHttpResponseContent(string $url, array $headers = [])
    {
        $client = new GuzzleClient($headers);
        $response = $client->get($url);
        $body = $response->getBody();
        return $body->getContents();
    }

}