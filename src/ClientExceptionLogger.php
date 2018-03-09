<?php

namespace ExceptionLogger;

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
		$status = (get_class($this->event) === 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException') ? $this->event->getStatusCode() : $this->event->getCode();
		
		$item = [
			'status_code' => $status,
			'url' => $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"],
			'message' => $this->event->getMessage(),
			'header' => $_SERVER['HTTP_USER_AGENT'],
			'body' => json_encode($this->event->getTrace()),
			'file' => $this->event->getFile(),
			'lineNumber' => $this->event->getLine(),
			'request_uri' => request()->getRequestUri(),
			'server_name' => request()->server('SERVER_NAME'),
			'enviroment' => env('APP_ENV'),
			'project_id' => env('EXCEPTION_LOGGER_PROJECT_ID'),
		];


		$this->client->request('POST', env('EXCEPTION_LOGGER').'/api/log', [
			    'headers' => [
			        'Accept' => 'application/test',
			        'Authorization' => 'Bearer '.$this->accessToken,
			    ],

			    'form_params' => [
			        'event_content' => json_encode($item),
			    ],
		]);
	}
}