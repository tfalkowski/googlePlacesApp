<?php
namespace kahlan\support;

/**
 * Class AppRequestMock
 *
 * @package kahlan\support
 */
class AppRequestMock
{
    /**
     * @type \Slim\App
     */
    protected $app;

    /**
     * AppRequestMock constructor.
     */
    public function __construct()
    {
        $this->app = $this->createApp();
    }

    /**
     * @param        $url
     * @param string $method
     * @param string $queryString
     *
     * @return \Slim\Http\Request
     */
    public function createRequest($url, $method = 'GET', $queryString = '')
    {
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $url,
            'QUERY_STRING' => $queryString
        ]);
        return \Slim\Http\Request::createFromEnvironment($environment);
    }

    /**
     * @param $request
     *
     * @return mixed
     */
    public function getResponse($request)
    {
        $app = $this->app;
        $response = $app($request, new \Slim\Http\Response());
        $response->getBody()->rewind();
        return $response;
    }

    /**
     * @return \Slim\App
     */
    private function createApp()
    {
        $path = __DIR__ . '/../../../src/';
        $settings = require $path . 'settings.php';
        $app = new \Slim\App($settings);
        require $path . 'dependencies.php';
        require $path . 'middleware.php';
        require $path . 'routes.php';
        return $app;
    }
}
