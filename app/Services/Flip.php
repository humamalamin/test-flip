<?php

namespace App\Services;


use App\Traits\CanHttpRequest;
use GuzzleHttp\Client;

class Flip
{
    use CanHttpRequest;

    /**
     * Create elena class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client(
            [
            'base_uri' => config('services.flip.url'),
            'auth' => [config('services.flip.token'), null],
            'headers' => $this->getDefaultHeaders(),
            ]
        );
    }

    /**
     * Get default headers for Client.
     *
     * @return array
     */
    public function getDefaultHeaders()
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Get all elena data.
     *
     * @param  string $endpoint
     * @param  array  $params
     * @return array
     */
    public function getAll($endpoint, $params = [])
    {
        return $this->get($endpoint, $params);
    }

    /**
     * Get elena data by ID.
     *
     * @param  string $endpoint
     * @param  int    $elenaId
     * @param  array  $params
     * @return array
     */
    public function getByID($endpoint, $transactionId, $params = [])
    {
        return $this->get($endpoint.'/'.$transactionId, $params);
    }

    /**
     * Create new elena data.
     *
     * @param  string $endpoint
     * @param  array  $data
     * @return array
     */
    public function createNew($endpoint, $data)
    {
        return $this->post($endpoint, $data);
    }
}