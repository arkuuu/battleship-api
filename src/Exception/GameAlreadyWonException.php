<?php

declare(strict_types=1);

namespace App\Exception;

class GameAlreadyWonException extends \Exception
{

    /**
     * @var string
     */
    private $winner;


    public function getWinner(): string
    {
        return $this->winner;
    }


    public function setWinner(string $winner): void
    {
        $this->winner = $winner;
    }
}
