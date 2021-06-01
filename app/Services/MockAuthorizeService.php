<?php


namespace App\Services;


use GuzzleHttp\Client;

class MockAuthorizeService
{
    /**
     * @var string
     */
    private $baseUrl = 'https://run.mocky.io';
    /**
     * @var array
     */
    private $allowedHttpVerbs = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * @param array $formData
     * @return Client
     */
    private function getClient($formData = []): Client
    {
        return new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'content-type' => 'application/json'
            ],
            'body' => json_encode($formData)
        ]);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @return mixed
     */
    private function doRequest(string $method, string $url, array $data = []) : mixed
    {
        $method = strtoupper($method);
        $allowedHttpVerbs = $this->allowedHttpVerbs;
        if (!in_array($method, $allowedHttpVerbs)) {
            throw new \InvalidArgumentException(
                "The supplied http method is invalid. Allowed values are " .
                implode(',', $allowedHttpVerbs) . '. Supplied value: ' . $method
            );
        }

        $response = $this->getClient($data)->request($method, $url);
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @return mixed
     */
    public function authorizeTransaction() : mixed
    {
        return $this->doRequest('GET', 'v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
    }
}
