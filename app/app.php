<?php

use Application\Application;
use Application\Provider\TwigServiceProvider;
use Application\Provider\YamlConfigServiceProvider;
use Application\Routing\Router;
use Application\Provider\YamlRoutingServiceProvider;
use Application\Provider\ConsoleServiceProvider;

$router = new Router();
$application = new Application($router);


$application->register(new YamlConfigServiceProvider(__DIR__ . '/config/services.yml'));
$application->register(new YamlRoutingServiceProvider(__DIR__ . '/config/routing.yml'));

$application->register(new ConsoleServiceProvider($application->get('shortest-sub-string-command')));
//echo '<pre>';print_r($application->container);echo '</pre>';exit;
$application->register(new TwigServiceProvider(array(
    'path' => __DIR__.'/../views',
)));
