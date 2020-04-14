<?php

declare(strict_types=1);

namespace App\ComputerPlayer\Strategy\ShipPlacement;

interface ShipPlacementStrategy
{

    /**
     * @param \App\Game\Board $ownBoard
     * @param \App\Game\Ship[]  $playableShips
     */
    public function placeShips(\App\Game\Board $ownBoard, array $playableShips): void;
}
