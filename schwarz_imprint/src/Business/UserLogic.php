<?php
namespace App\Business;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserLogic
{
    private $client;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->client = $httpClient;
    }

    // public function fetchUsers(): string
    public function fetchUsers(string $apiEndpoint)
    {
        $response = $this->client->request(
            'GET',
            $apiEndpoint,

        );

        if ($response->getStatusCode() !== 200) {
            // TODO: Replace with user-defined exception
            throw new Exception('could not fetch users from API');
        } 
      
        $headers = $response->getHeaders();
        if (!isset($headers['content-type']) || 
        !str_contains(
            join(', ', $headers['content-type']),
            'application/json'
        )
        ) {
            throw new Exception('invalid content-type returned');
        }

        return $response->getContent();
    }
}
