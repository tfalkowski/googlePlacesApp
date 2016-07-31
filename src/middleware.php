<?php
// Application middleware

$app->add(new \Slim\HttpCache\Cache('public', 86400));

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => "/api/v1",
    "realm" => "Protected",
    "users" => [
        $container->get('settings')['auth']['login'] => $container->get('settings')['auth']['password']
    ],
]));
