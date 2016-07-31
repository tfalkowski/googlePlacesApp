<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],
        'googlePlaces' => [
            'apiKey' => 'AIzaSyDu18O3TK4FQgZzlJJgg47tHz3mmxkLQFs'
        ],
        'auth' => [
            'login' => 'user',
            'password' => 'password'
        ]
    ],
];
