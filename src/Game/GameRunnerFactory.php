<?php

declare(strict_types=1);

namespace App\Game;

class GameRunnerFactory
{

    public function createDefault(): GameRunner
    {
        return new GameRunner(
            new \App\ComputerPlayer\ComputerPlayer(
                new \App\ComputerPlayer\Strategy\ShipPlacement\OneShipPerRowHorizontally(),
                new \App\ComputerPlayer\Strategy\Shooting\Random()
            ),
            new ShipFactory()
        );
    }
}
