# Google Places Application - Rest API and GUI
Application is based on Slim Framework 3 Skeleton Application which is very easy and quick to use.

## Installation
If you have installed Composer globally then in project directory run:

    $ composer install

## Usage
For PHP built-in server, in project directory run:

    $ php -S localhost:8000 -t public public/index.php

### GUI
To run GUI go to:

    http://localhost:8000

### Rest API v1
Rest API v1 is protected by HTTP Basic Authentication which requires login and password.

Use default user:

    Login: user
    Password: password

API responses are available in JSON format. They are also cacheable using HTTP cache.

To run Rest API go to:

    http://localhost:8000/api/v1/

It displays 'Rest API v1!' information.

## Available methods

Method "/bars"

    GET: /bars

Returns data of bars in Gdańsk located within 2 km radius around Neptun Fountain

Example

    http://localhost:8000/api/v1/bars

Method "bars/latitude/longitude"

    GET: /bars/latitude/longitude

Returns data of bars located within 2 km radius around your position (based on Geolocation - if it's available on your device)    

Example

    http://localhost:8000/api/v1/bars/54.34853/18.65324

## Tests
Rest API and also GUI are covered by Kahlan tests.
To execute tests in project directory run:

    $ tests/kahlan.sh

## Requirements

 - PHP v5.5 at least
 - ensure 'logs/' directory is web writeable.
