<?php
use Kahlan\Plugin\Stub;
use kahlan\support\AppRequestMock;

describe("Application GUI", function() {

    before(function() {
        $this->app = new AppRequestMock();
    });

    describe("GUI", function () {

        it("displays map container when requests is for base url", function () {
            $request = $this->app->createRequest('/');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toMatch('/map-container/');
        });

        it("displays 'Find Bars nearby Neptun Fountain' button", function () {
            $request = $this->app->createRequest('/');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toContain('Find bars nearby Neptun Fountain');
        });

        it("displays 'Find Bars near me' button", function () {
            $request = $this->app->createRequest('/');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toContain('Find bars near me');
        });

        it("returns 404 error response when request is for dummy url", function () {
            $request = $this->app->createRequest('/dummy-url');
            $response = $this->app->getResponse($request);
            expect($response->getBody()->getContents())->toBe('{"code":404,"message":"Not found. Request method not exists."}');
        });

    });

    after(function() {
        $this->app = null;
    });
});
