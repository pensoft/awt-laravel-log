<?php

return [
    'host' => [env('ELASTICSEARCH_HOST', 'http://localhost:9200')],
    'ssl_verification' => env('ELASTICSEARCH_SSL_VERIFICATION', false),
    'username' => env('ELASTICSEARCH_USERNAME', ''),
    'password' => env('ELASTICSEARCH_PASSWORD', ''),
    'index' => env('ELASTICSEARCH_LOG_INDEX', 'monolog'),
    'type' => env('ELASTICSEARCH_TYPE', 'doc')
];
