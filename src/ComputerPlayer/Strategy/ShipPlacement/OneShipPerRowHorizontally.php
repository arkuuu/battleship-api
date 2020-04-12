<?php

declare(strict_types=1);

namespace App\ComputerPlayer\Strategy\ShipPlacement;

class OneShipPerRowHorizontally implements ShipPlacementStrategy
{

    /**
     * @inheritDoc
     */
    public function placeShips(\App\Game\Board $ownBoard, array $playableShips): void
    {
        $row = 0;
        foreach ($playableShips as $ship) {
            $ownBoard->placeShip(
                $ship,
                new \App\Game\Position($row, 0),
                \App\Game\Board::DIRECTION_HORIZONTAL
            );
            $row++;
        }
    }
}
