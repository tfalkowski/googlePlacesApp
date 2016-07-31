<?php
ini_set('memory_limit', '4G');
date_default_timezone_set('Europe/London');
$args = $this->args();
$args->argument('spec', 'default', 'tests/kahlan/spec');
$args->argument('src', 'default', [
    'controllers'
]);
