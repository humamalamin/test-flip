<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

/**
 * This trait for service third party
 */
trait CanHttpRequest
{
    /**
     * a guzzle client
     * 
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Contains the last response the request client sent
     * 
     * @var array
     */
    protected $response;

    /**
     * Get the client.
     *
     * @return $client
     *
     * @codeCoverageIgnore
     */
    public function getClient()
    {
        if ($this->client instanceof Client) {
            return $this->client;
        }

        return new Client;
    }

    /**
     * Set the client.
     *
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Set the response.
     *
     * @return $response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Wrapper for the GET request
     *
     * @param string $endpoint
     * @param array $params
     * @param array @headers
     * @return response
     */
    public function get($endpoint, $params = [], $headers = null)
    {
        return $this->sendRequest(
            'GET', 
            $endpoint,
            [
                'query' => $params,
                'headers' => $headers,
            ]
        );
    }

    /**
     * Wrapper for the POST request
     *
     * @param string $endpoint
     * @param array $params
     * @param array @headers
     * @return response
     */
    public function post($endpoint, $params = [], $headers = null)
    {
        return $this->sendRequest(
            'POST', 
            $endpoint, 
            [
                'json' => $params,
                'headers' => $headers,
            ]
        );
    }

    /**
     * Perform the HTTP request
     *
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @return object
     */
    protected function sendRequest($method, $endpoint, $params)
    {
        try {
            // To prevent overwriting, unset headers key if no specified headers present
            if (is_null($params['headers'])) {
                unset($params['headers']);
            }

            $response = $this->getClient()->request($method, $endpoint, $params);

            $this->response = $response;
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
        }

        return json_decode((string) $this->getResponse()->getBody(), true);
    }
}
