<?php

namespace deferdie\ExceptionLogger\Facades;

use Illuminate\Support\Facades\Facade;

class ClientExceptionLoggerFacade extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'deferdie-ExceptionLogger';
	}
}