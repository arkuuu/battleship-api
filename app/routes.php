<?php

declare(strict_types=1);

$app->post('/game', \App\Action\CreateNewGameAction::class);
$app->group('/game/{token}', function (\Slim\Routing\RouteCollectorProxy $group) {
    $group->get('', \App\Action\GetGameAction::class);
    $group->post('/ship', \App\Action\PlaceShipAction::class);
    $group->post('/shot', \App\Action\FireShotAction::class);
});
