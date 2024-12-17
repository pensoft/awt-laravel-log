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
    if (class_exists(\Pensoft\AwtLaravelLog\LogElasticsearchService::class)) {
        app(\Pensoft\AwtLaravelLog\LogElasticsearchService::class)($e);
    }
});
```

### Explanation of the Code
1. **`class_exists(\Pensoft\AwtLaravelLog\LogElasticsearchService::class)`**: Checks if the Elasticsearch logging service class exists before invoking it.
2. **`app(\Pensoft\AwtLaravelLog\LogElasticsearchService::class)($e)`**: Resolves the service from the Laravel service container and calls it with the exception `$e`. This logs the exception to Elasticsearch.

---

## Configuration

The logging service will automatically integrate into your application's exception reporting pipeline. If you require custom Elasticsearch configurations (host, index, or other settings), you may extend the `LogElasticsearchService` or override settings in your Laravel application's service provider.

---

## Requirements

- Laravel 8.x or higher
- PHP 8.x or higher
- Elasticsearch server (if connecting directly to a server endpoint)


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


