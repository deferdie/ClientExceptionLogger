<?php

namespace deferdie\ExceptionLogger;

use GuzzleHttp\Client;

class ClientExceptionLogger
{
	private $accessToken;

	private $client;

	private $event;

	public function __construct()
	{
		$this->client = new Client;
	}

	public function log($event)
	{
		$this->getAccessToken();

		$this->event = $event;

		$this->dispatch();
	}

	private function getAccessToken()
	{
		$response = $this->client->post(env('EXCEPTION_LOGGER').'/oauth/token', [
		    'form_params' => [
		        'grant_type' => 'client_credentials',
		        'client_id' => env('EXCEPTION_LOGGER_CLIENT_ID'),
		        'client_secret' => env('EXCEPTION_LOGGER_CLIENT_SECRET'),
		        'scope' => '',
		    ],
		]);

		$this->accessToken = json_decode((string) $response->getBody(), true)['access_token'];
	}

	public function dispatch()
	{
		$this->client->request('POST', 'http://exceptionlogger.test/api/log', [
			    'headers' => [
			        'Accept' => 'application/test',
			        'Authorization' => 'Bearer '.$this->accessToken,
			    ],

			    'form_params' => [
			        'event_content' => json_encode($this->event),
			    ],
		]);
	}
}