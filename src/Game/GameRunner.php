<?php

declare(strict_types=1);

namespace App\Game;

class GameRunner
{

    /**
     * @var Game
     */
    private $game;

    /**
     * @var \App\ComputerPlayer\ComputerPlayer
     */
    private $computerPlayer;

    /**
     * @var ShipFactory
     */
    private $shipFactory;


    public function __construct(
        \App\ComputerPlayer\ComputerPlayer $computerPlayer,
        ShipFactory $shipFactory
    ) {
        $this->computerPlayer = $computerPlayer;
        $this->shipFactory = $shipFactory;
    }


    public function setGame(Game $game)
    {
        $this->game = $game;
    }


    public function startGame(): void
    {
        if ($this->game->getState() !== Game::STATE_CREATED) {
            throw new \App\Exception\InvalidActionException('Game already started');
        }

        $availableShips = $this->shipFactory->buildAll();
        $this->computerPlayer->placeShips($this->game->getComputerBoard(), $availableShips);
    }


    public function playerPlacesShip(Ship $ship, Position $position, string $direction)
    {
        if (!$this->game->getState() === Game::STATE_CREATED) {
            throw new \App\Exception\InvalidActionException('Not allowed to place ships now');
        }

        $this->game->getPlayerBoard()->placeShip($ship, $position, $direction);

        if ($this->allShipsPlaced()) {
            if ($this->computerStarts()) {
                $this->game->setState(Game::STATE_WAITING_FOR_COMPUTER);
            } else {
                $this->game->setState(Game::STATE_WAITING_FOR_PLAYER);
            }
            $this->nextTurn();
        }
    }


    private function computerStarts(): bool
    {
        return mt_rand(0, 2) > 1;
    }


    private function allShipsPlaced(): bool
    {
        $allShips = count($this->shipFactory->buildAll());

        return count($this->game->getPlayerBoard()->getPlacedShips()) === $allShips;
    }


    public function playerShoots(Position $position): ShotResult
    {
        if (!$this->isPlayersTurn()) {
            throw new \App\Exception\InvalidActionException('Not allowed to shoot now');
        }

        $result = $this->game->getComputerBoard()->receiveShot($position);
        if (!$result->isHit) {
            $this->game->setState(Game::STATE_WAITING_FOR_COMPUTER);
        }
        $this->nextTurn();

        return $result;
    }


    private function nextTurn(): void
    {
        if ($this->game->hasWinner()) {
            $this->game->setState(Game::STATE_FINISHED);
            return;
        }

        if ($this->isComputersTurn()) {
            $this->computerPlayer->shoot($this->game->getPlayerBoard());
            $this->game->setState(Game::STATE_WAITING_FOR_PLAYER);
        }
    }


    private function isPlayersTurn(): bool
    {
        return $this->game->getState() === Game::STATE_WAITING_FOR_PLAYER;
    }


    private function isComputersTurn(): bool
    {
        return $this->game->getState() === Game::STATE_WAITING_FOR_COMPUTER;
    }
}
