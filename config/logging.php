<?php

return [
    'elasticsearch' => [
        'driver' => 'monolog',
        'handler' => \Monolog\Handler\ElasticsearchHandler::class,
        'level' => env('LOG_LEVEL', 'debug'),
        'processors' => [\Pensoft\AwtLaravelLog\Processors\DefaultContextProcessor::class],
        'with' => (env('APP_ENV') != 'testing' && !app()->runningInConsole())  ? [
            'client' => \Elastic\Elasticsearch\ClientBuilder::create()
                ->setHosts([env('ELASTICSEARCH_HOST', 'http://')])
                ->setSSLVerification(env('ELASTICSEARCH_SSL_VERIFICATION', false))
                ->setBasicAuthentication(
                    env('ELASTICSEARCH_USERNAME', ''),
                    env('ELASTICSEARCH_PASSWORD', '')
                )
                ->build(),
            'options' => [
                'index' => env('ELASTICSEARCH_LOG_INDEX', 'monolog'),
                'ignore_error' => false
            ],
            'level' => env('LOG_LEVEL', 'debug'),
        ] : [],
        'formatter' => \Monolog\Formatter\ElasticsearchFormatter::class,
        'formatter_with' => [
            'index' => env('ELASTICSEARCH_LOG_INDEX', 'monolog'),
            'type' => 'doc'
        ]
    ]
];
