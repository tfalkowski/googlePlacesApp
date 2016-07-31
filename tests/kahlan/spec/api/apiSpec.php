<?php
use Kahlan\Plugin\Stub;
use kahlan\support\AppRequestMock;

describe("Application Rest API", function() {

    before(function() {
        $this->app = new AppRequestMock();
    });

    describe("Rest API", function () {

        it("returns 405 error response when request is POST method", function () {
            $request = $this->app->createRequest('/api/v1/bars', 'POST');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toBe('{"code":405,"message":"Method not allowed. Must be one of: GET"}');
        });

        it("returns 404 error response when request action is '/api'", function () {
            $request = $this->app->createRequest('/api');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toBe('{"code":404,"message":"Not found. Request method not exists."}');
        });

        it("returns 404 error response when request action is '/api/v1'", function () {
            $request = $this->app->createRequest('/api/v1');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toBe('{"code":404,"message":"Not found. Request method not exists."}');
        });

        it("returns 404 error response when request action match but contains trailing slash", function () {
            $request = $this->app->createRequest('/api/v1/bars/');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toBe('{"code":404,"message":"Not found. Request method not exists."}');
        });

        it("returns 404 error response when request is '/api/v1/dummy'", function () {
            $request = $this->app->createRequest('/api/v1/dummy');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toBe('{"code":404,"message":"Not found. Request method not exists."}');
        });

        it("returns API information when request action match '/api/v1/'", function () {
            $request = $this->app->createRequest('/api/v1/');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toBe('Rest API v1!');
        });

        it("returns bars data in json format when request action match '/api/v1/bars'", function () {
            $request = $this->app->createRequest('/api/v1/bars');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toContain('OK');
        });

        it("returns bars data in json format when request action match '/api/v1/bars/54.34853/18.65324'", function () {
            $request = $this->app->createRequest('/api/v1/bars/54.34853/18.65324');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toContain('OK');
        });

        it("returns 404 error response when request action has only one parameter '/api/v1/bars/54.34853'", function () {
            $request = $this->app->createRequest('/api/v1/bars/54.34853');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toBe('{"code":404,"message":"Not found. Request method not exists."}');
        });

    });

    after(function() {
        $this->app = null;
    });
});
