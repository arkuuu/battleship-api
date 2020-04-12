<?php

declare(strict_types=1);

namespace App\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateNewGameAction implements Action
{

    /**
     * @var \App\Game\GameBuilder
     */
    private $gameBuilder;

    /**
     * @var \App\Model\GameModel
     */
    private $model;

    /**
     * @var \App\Game\GameRunnerFactory
     */
    private $factory;


    public function __construct(
        \App\Game\GameBuilder $builder,
        \App\Model\GameModel $model,
        \App\Game\GameRunnerFactory $factory
    ) {
        $this->gameBuilder = $builder;
        $this->model = $model;
        $this->factory = $factory;
    }


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $game = $this->gameBuilder->build();

        $runner = $this->factory->createDefault();
        $runner->setGame($game);
        $runner->startGame();

        $this->model->save($game);

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['token' => $game->getToken()]));

        return $response;
    }
}
