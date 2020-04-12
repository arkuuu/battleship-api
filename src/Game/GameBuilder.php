<?php

declare(strict_types=1);

namespace App\Game;

class GameBuilder
{

    /**
     * @var \App\TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(\App\TokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    public function build(): Game
    {
        $game = new Game();
        $game->setToken($this->tokenGenerator->generate());
        $game->setState($game::STATE_CREATED);

        $playerBoard = new Board();
        $game->setPlayerBoard($playerBoard);

        $computerBoard = new Board();
        $game->setComputerBoard($computerBoard);

        return $game;
    }
}
