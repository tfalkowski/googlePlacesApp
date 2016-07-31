<?php
// DIC configuration

$container = $app->getContainer();

$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

$container['googlePlaces'] = function ($c) {
    return new \SKAgarwal\GoogleApi\PlacesApi($c->get('settings')['googlePlaces']['apiKey']);
};

$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};

$container['errorHandler'] = function ($c) {
    return function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, \Exception $exception) use ($c) {
        return $response->withJson([
            'code' => 500,
            'message' => 'Internal server error.'
        ]);
    };
};

$container['notFoundHandler'] = function ($c) {
    return function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response) use ($c) {
        return $response->withJson([
            'code' => 404,
            'message' => 'Not found. Request method not exists.'
        ]);
    };
};

$container['notAllowedHandler'] = function ($c) {
    return function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $methods) use ($c) {
        return $response->withJson([
            'code' => 405,
            'message' => 'Method not allowed. Must be one of: ' . implode(', ', $methods)
        ]);
    };
};
