<?php

namespace NotificationChannel;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHTtp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use NotificationChannel\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

class Smso
{
    /**
     * @var string Smso API URL.
     */
    protected $apiUrl = 'https://app.smso.ro/api/v1/send';

    /**
     * @var HttpClient HTTP Client.
     */
    protected $http;

    /**
     * @var null|string Smso API Key.
     */
    protected $apiKey;

    /**
     * @param  string  $apiKey
     * @param  HttpClient  $http
     */
    public function __construct(string $apiKey = null, HttpClient $http = null)
    {
        $this->apiKey = $apiKey;
        $this->http = $http;
    }

    /**
     * Get API key.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Set API key.
     *
     * @param  string  $apiKey
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Get HttpClient.
     *
     * @return HttpClient
     */
    protected function httpClient(): HttpClient
    {
        return $this->http ?? new HttpClient();
    }

    /**
     * Send text message.
     *
     * <code>
     * $params = [
     *      'to'                    => '',
     *      'body'                  => '',
     *      'sender'                  => '',
     * ];
     * </code>
     *
     *
     * @param array $params
     * @return ResponseInterface
     * @throws CouldNotSendNotification
     */
    public function sendMessage(array $params)
    {
        return $this->sendRequest('sms', $params);
    }

    /**
     * @throws GuzzleException
     * @throws CouldNotSendNotification
     */
    public function sendRequest(string $endpoint, array $params)
    {
        if (empty($this->apiKey)) {
            throw CouldNotSendNotification::apiKeyNotProvided();
        }

        try {
            return $this->httpClient()->post($this->apiUrl, [
                'headers' => [
                    'X-Authorization' => $this->apiKey,
                ],
                'form_params' => $params,
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceNotAvailable($exception);
        }
    }
}
