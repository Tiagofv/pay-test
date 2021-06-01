<?php


namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

abstract class BaseService
{
    protected $baseUrl;
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
    protected function doRequest(string $method, string $url, array $data = []): mixed
    {
        $method = strtoupper($method);
        $allowedHttpVerbs = $this->allowedHttpVerbs;
        if (!in_array($method, $allowedHttpVerbs)) {
            throw new \InvalidArgumentException(
                "The supplied http method is invalid. Allowed values are " .
                implode(',', $allowedHttpVerbs) . '. Supplied value: ' . $method
            );
        }

        try {
            $response = $this->getClient($data)->request($method, $url);
            return json_decode($response->getBody()->getContents());
        } catch (RequestException $requestException) {
            return [
                'success' => false,
                'message' => 'There seems to be a problem with your internet connection.',
                'response' => $requestException->getResponse()
            ];
        } catch (ClientException $clientException) {
            return [
                'success' => false,
                'message' => 'There seems to be a problem with the entered data.',
                'response' => $clientException->getResponse()
            ];
        } catch (ServerException $serverException) {
            return [
                'success' => false,
                'message' => 'The server is unaivailable or presents error.',
                'response' => $serverException->getResponse()
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => 'Unexpected error.',
                'response' => $exception->getMessage()
            ];
        }

    }
}
