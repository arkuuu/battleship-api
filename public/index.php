<?php

declare(strict_types=1);

require_once "../vendor/autoload.php";

$container = require "../app/dependencies.php";
$app = \Slim\Factory\AppFactory::createFromContainer($container);

// $routeCollector = $app->getRouteCollector();
// $routeCollector->setCacheFile('/tmp/cache_routes.cache');

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

require "../app/routes.php";

$app->run();
