# pensoft/awt-laravel-log

`pensoft/awt-laravel-log` is a Composer package designed to integrate Elasticsearch logging into your Laravel application seamlessly. It provides a custom log service for exception handling, allowing you to log exception events into Elasticsearch for enhanced observability.

---

## Installation

You can install the package using Composer:

```bash
composer require pensoft/awt-laravel-log
```

---

## Usage Instructions

After installing the package, you need to register the logging service with Laravel's exception reporting pipeline. This is done by modifying the `reportable()` method within your `App\Exceptions\Handler` class.

### 1. Modify the `Handler.php` file
You will integrate the logging service by adding the following code inside the `reportable()` method:

```php
use Throwable;

$this->reportable(function (Throwable $e) {
    if (class_exists(\Pensoft\AwtLaravelLog\Facades\Log::class)) {
        \Pensoft\AwtLaravelLog\Facades\Log::throw($e)
    }
});
```

### 2. Log to Elasticsearch using Laravel's Log facade

You can log messages to Elasticsearch by using the `Log` facade, specifying the `elasticsearch` channel that you've defined in the `config/logging.php` file.

```php
use Illuminate\Support\Facades\Log;

Log::channel('elasticsearch')->error('Message here!', $context = []);
```

- **channel**: Here, we specify the `elasticsearch` channel, which was defined in the configuration file.
- **error**: The log level (in this case, an error). You can use other log levels like `info`, `warning`, `critical`, etc., depending on your needs.
- **context**: An optional array of context that can be passed to provide more details along with the log message.

### 3. Use Pensoft's Custom Log Facade

You can also use the custom logging facade provided by the package to log messages and exceptions directly.

```php
use Pensoft\AwtLaravelLog\Facades\Log;

Log::error('Message here!', $context = []);
```

- **error**: Log the message at the error level.

### 4. Log Exceptions

To log exceptions to Elasticsearch, you can use the `throw()` method. This will automatically capture the exception and send it to Elasticsearch.

```php
use Pensoft\AwtLaravelLog\Facades\Log;

Log::throw(new \Exception('Message here!', $code = 500));
```

- **throw**: This method is used to log exceptions, allowing you to specify the exception and an optional status code.

When you call `Log::throw($exception)`, the following context is automatically captured and sent to Elasticsearch:

**code**: The exception’s error code.  
**line**: The line number where the exception was thrown.  
**file**: The file path where the exception was thrown.  
**trace**: An array of the stack trace, which provides detailed information about the error.  
**traceAsString**: A string representation of the stack trace.  

---

## Configuration
To configure the package to use Elasticsearch, you can modify the config/logging.php file. You will add the following configuration to the file to define the necessary settings:

Example Configuration:
```php
return [
    'elasticsearch' => [
        'driver' => 'monolog',
        'level' => env('LOG_LEVEL', 'debug'),
        'elastic' => [
            'host' => [env('ELASTICSEARCH_HOST', 'http://')],
            'ssl_verification' => env('ELASTICSEARCH_SSL_VERIFICATION', false),
            'username' => env('ELASTICSEARCH_USERNAME', ''),
            'password' => env('ELASTICSEARCH_PASSWORD', ''),
            'index' => env('ELASTICSEARCH_LOG_INDEX', 'monolog'),
        ]
    ]
];
```
---

## Requirements

- Laravel 10.x or higher
- PHP 8.x or higher
- Elasticsearch server (if connecting directly to a server endpoint)
- Monolog 3.x


### Environment Variables to Configure
To make this configuration functional, you should define the necessary environment variables in your `.env` file. Below are the environment variables that must be configured:

```env
# Elasticsearch server host (URL)
ELASTICSEARCH_HOST=

# SSL Verification for Elasticsearch client
ELASTICSEARCH_SSL_VERIFICATION=

# Username for basic authentication
ELASTICSEARCH_USERNAME=

# Password for basic authentication
ELASTICSEARCH_PASSWORD=

# Log index name in Elasticsearch
ELASTICSEARCH_LOG_INDEX=monolog

# The default logging level for Elasticsearch
LOG_LEVEL=debug

```

