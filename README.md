# ClientExceptionLogger

## Install
composer require deferdie/exception

#### Register service provider
ExceptionLogger\ClientExceptionLoggerServiceProvider::class,

#### Register alias
'ClientException' => ExceptionLogger\Facades\ClientExceptionLoggerFacade::class,

### Add to .env file
EXCEPTION_LOGGER=http://exceptionlogger.test <br>
EXCEPTION_LOGGER_CLIENT_ID=1 <br>
EXCEPTION_LOGGER_CLIENT_SECRET=[your application secret] <br>
EXCEPTION_LOGGER_PROJECT_ID=1
