<?php

return [
    'title' => 'NunoPress',
    'description' => 'Website package for Laravel 5.3+',
    'author' => 'NunoPress LLC',
    'copyright' => sprintf('Copyright &copy; %s - NunoPress LLC', date('Y')),

    'http_client' => [
        'base_uri' => 'http://httpbin.org/',
        'auth' => []
    ],

    'http_cache_minutes' => 10,

    'http_request_params' => [
        'get' => [
            'method' => 'GET',
            'uri' => 'get'
        ],
        'post' => [
            'method' => 'POST',
            'uri' => 'post',
            'form_params' => [
                'name' => 'NunoPress'
            ]
        ]
    ]
];
