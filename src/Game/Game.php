<?php

declare(strict_types=1);

namespace App\Game;

class Game
{

    public const STATE_CREATED = 'created';
    public const STATE_WAITING_FOR_PLAYER = 'waiting_for_player';
    public const STATE_WAITING_FOR_COMPUTER = 'waiting_for_computer';
    public const STATE_FINISHED = 'finished';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $state;

    /**
     * @var Board
     */
    private $playerBoard;

    /**
     * @var Board
     */
    private $computerBoard;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getToken(): string
    {
        return $this->token;
    }


    public function setToken(string $token): void
    {
        $this->token = $token;
    }


    public function getState(): string
    {
        return $this->state;
    }


    public function setState(string $state): void
    {
        $this->state = $state;
    }


    public function getPlayerBoard(): Board
    {
        return $this->playerBoard;
    }


    public function setPlayerBoard(Board $playerBoard): void
    {
        $this->playerBoard = $playerBoard;
    }


    public function getComputerBoard(): Board
    {
        return $this->computerBoard;
    }


    public function setComputerBoard(Board $computerBoard): void
    {
        $this->computerBoard = $computerBoard;
    }


    public function hasWinner(): bool
    {
        return $this->getWinner() !== null;
    }

    public function getWinner(): ?string
    {
        if ($this->computerBoard->allShipsSunken()) {
            return Player::PLAYER;
        } elseif ($this->playerBoard->allShipsSunken()) {
            return Player::COMPUTER;
        }

        return null;
    }
}
