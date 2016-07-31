<?php
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

/**
 * GUI route
 */
$app->get('/', function(Request $request, Response $response, $args) {
    return $this->view->render($response, 'index.phtml', ['apiKey' => $this->settings['googlePlaces']['apiKey']]);
});

/**
 * Rest API route
 */
$app->group('/api', function () {
    $this->group('/v1', function () {
        $this->get('/', function(Request $request, Response $response, $args) {
            return $response->write('Rest API v1!');
        });

        $this->get('/bars[/{lat:[0-9\.]+}/{lng:[0-9\.]+}]', '\controllers\BarController:search');
    });
});
