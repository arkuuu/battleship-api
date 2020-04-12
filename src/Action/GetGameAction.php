<?php

declare(strict_types=1);

namespace App\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetGameAction implements Action
{

    /**
     * @var \App\Model\GameModel
     */
    private $gameModel;


    public function __construct(\App\Model\GameModel $gameModel)
    {
        $this->gameModel = $gameModel;
    }


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $token = $args['token'];
        if (!$token) {
            throw new \InvalidArgumentException();
        }

        $game = $this->gameModel->findByToken($token);
        if (!$game) {
            throw new \Slim\Exception\HttpNotFoundException($request);
        }

        $response->getBody()->write(var_export($game, true));

        return $response;
    }
}
