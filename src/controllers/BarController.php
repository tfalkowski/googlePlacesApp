<?php
namespace controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class BarController
 *
 * @package controllers
 */
class BarController extends BaseController
{
    /**
     * @type string
     */
    protected $keyword = 'bar';

    /**
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param                     $args
     *
     * @return mixed
     * @throws \Exception
     */
    public function search(Request $request, Response $response, $args)
    {
        $this->nearBySearch($args);

        $response = $response->withJson([
            'code' => 200,
            'message' => 'OK',
            'itemsCount' => count($this->getData()),
            'results' => $this->getData()
        ]);

        return $this->cache->withExpires($response, $this->cacheDuration());
    }
}
