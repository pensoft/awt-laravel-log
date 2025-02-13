<?php

return [
    'elasticsearch' => [
        'driver' => 'monolog',
        'level' => env('LOG_LEVEL', 'debug'),
        'channel' => 'elasticsearch',
        'elastic' => [
            'host' => [env('ELASTICSEARCH_HOST', 'http://')],
            'ssl_verification' => env('ELASTICSEARCH_SSL_VERIFICATION', false),
            'username' => env('ELASTICSEARCH_USERNAME', ''),
            'password' => env('ELASTICSEARCH_PASSWORD', ''),
            'index' => env('ELASTICSEARCH_LOG_INDEX', 'monolog'),
        ]
    ]
];
