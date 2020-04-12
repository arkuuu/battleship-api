<?php

declare(strict_types=1);

$container = new \DI\Container();

$container->set(\PDO::class, function () {
    return new PDO(
        'mysql:dbname=battleship-api;host=db',
        'root',
        'mysql',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );
});

// Auto-wiring takes care of all the other dependencies

return $container;
