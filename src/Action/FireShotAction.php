<?php

declare(strict_types=1);

namespace App\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FireShotAction implements Action
{

    /**
     * @var \App\Game\GameRunnerFactory
     */
    private $factory;

    /**
     * @var \App\Model\GameModel
     */
    private $gameModel;

    /**
     * @var \App\Game\PositionTranslator
     */
    private $positionTranslator;


    public function __construct(
        \App\Game\GameRunnerFactory $factory,
        \App\Model\GameModel $gameModel,
        \App\Game\PositionTranslator $positionTranslator
    ) {
        $this->factory = $factory;
        $this->gameModel = $gameModel;
        $this->positionTranslator = $positionTranslator;
    }


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $token = $args['token'];
        $game = $this->gameModel->findByToken($token);
        if (!$game) {
            throw new \Slim\Exception\HttpNotFoundException($request);
        }
        if ($game->hasWinner()) {
            $e = new \App\Exception\GameAlreadyWonException();
            $e->setWinner($game->getWinner());
            throw $e;
        }

        $body = json_decode($request->getBody()->getContents(), true);
        $position = $this->positionTranslator->translate($body['field']);

        $runner = $this->factory->createDefault();
        $runner->setGame($game);
        $shotResult = $runner->playerShoots($position);

        $this->gameModel->save($game);

        $output = ['shotResult' => $shotResult];
        if ($game->hasWinner()) {
            $output['winner'] = $game->getWinner();
        }

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($output));

        return $response;
    }
}
