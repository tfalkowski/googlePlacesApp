<?php
namespace controllers;

use Interop\Container\ContainerInterface;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class BaseController
 *
 * @package controllers
 */
abstract class BaseController
{
    const NEPTUN_FOUNTAIN_CORDS = '54.34853,18.65324';

    /**
     * @type int
     */
    protected $radius = 2000;
    /**
     * @type string
     */
    protected $language = 'en-GB';
    /**
     * @type string
     */
    protected $keyword = '';
    /**
     * @type mixed
     */
    protected $googlePlaces;
    /**
     * @type \Interop\Container\ContainerInterface
     */
    protected $ci;
    /**
     * @type mixed
     */
    protected $cache;
    /**
     * @type array
     */
    private $data = [];

    /**
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param                     $args
     *
     * @return mixed
     */
    abstract public function search(Request $request, Response $response, $args);

    /**
     * BaseController constructor.
     *
     * @param \Interop\Container\ContainerInterface $ci
     */
    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
        $this->googlePlaces = $this->ci->get('googlePlaces');
        $this->cache = $this->ci->get('cache');
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $args
     * @param array $options
     *
     * @throws \Exception
     */
    protected function nearBySearch(array $args = [], array $options = [])
    {
        try {
            $response = $this->parseResponse($this->googlePlaces->nearBySearch($this->location($args), $this->radius, array_merge($this->options(), $options)));
            if (!empty($response['next_page_token'])) {
                sleep(2); // this is BAD and it's only because I didn't have enought time for limit/offset implementation
                $this->nearBySearch($args, ['pagetoken' => $response['next_page_token']]);
            }
        } catch (GooglePlacesApiException $e) {
            if (!$this->data) {
                throw new \Exception('Service Unavailable');
            }
        }
    }

    /**
     * @return int
     */
    protected function cacheDuration()
    {
        return time() + 3600;
    }

    /**
     * @param $response
     *
     * @return mixed
     */
    private function parseResponse($response)
    {
        $response = json_decode(json_encode($response), true);
        if (!empty($response['results'])) {
            $this->data = array_merge($this->data, $response['results']);
        }
        return $response;
    }

    /**
     * @param array $args
     *
     * @return string
     */
    private function location(array $args = [])
    {
        return $args ? implode(',', $args) : self::NEPTUN_FOUNTAIN_CORDS;
    }

    /**
     * @return array
     */
    private function options()
    {
        return ['keyword' => $this->keyword, 'language' => $this->language];
    }
}
