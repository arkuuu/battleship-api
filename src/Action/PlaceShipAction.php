<?php

declare(strict_types=1);

namespace App\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PlaceShipAction implements Action
{

    /**
     * @var \App\Game\GameRunnerFactory
     */
    private $factory;

    /**
     * @var \App\Model\GameModel
     */
    private $model;

    /**
     * @var \App\Game\ShipFactory
     */
    private $shipFactory;

    /**
     * @var \App\Game\PositionTranslator
     */
    private $positionTranslator;


    public function __construct(
        \App\Game\GameRunnerFactory $factory,
        \App\Model\GameModel $model,
        \App\Game\ShipFactory $shipFactory,
        \App\Game\PositionTranslator $positionTranslator
    ) {
        $this->factory = $factory;
        $this->model = $model;
        $this->shipFactory = $shipFactory;
        $this->positionTranslator = $positionTranslator;
    }


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $token = $args['token'];
        $game = $this->model->findByToken($token);
        if (!$game) {
            throw new \Slim\Exception\HttpNotFoundException($request);
        }

        $body = json_decode($request->getBody()->getContents(), true);
        $ship = $this->shipFactory->build($body['ship']);
        $position = $this->positionTranslator->translate($body['field']);
        $direction = $body['direction'];

        $runner = $this->factory->createDefault();
        $runner->setGame($game);
        $runner->playerPlacesShip($ship, $position, $direction);

        $this->model->save($game);

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(
            json_encode(
                ['battleground' => $game->getPlayerBoard()->getBattleground()]
            )
        );

        return $response;
    }
}
