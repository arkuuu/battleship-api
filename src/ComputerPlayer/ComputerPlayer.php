<?php

declare(strict_types=1);

namespace App\ComputerPlayer;

class ComputerPlayer
{

    /**
     * @var \App\ComputerPlayer\Strategy\ShipPlacement\ShipPlacementStrategy
     */
    private $shipPlacementStrategy;

    /**
     * @var \App\ComputerPlayer\Strategy\Shooting\ShootingStrategy
     */
    private $shotFiringStrategy;


    public function __construct(
        Strategy\ShipPlacement\ShipPlacementStrategy $shipPlacementStrategy,
        Strategy\Shooting\ShootingStrategy $shotFiringStrategy
    ) {
        $this->shipPlacementStrategy = $shipPlacementStrategy;
        $this->shotFiringStrategy = $shotFiringStrategy;
    }


    public function placeShips(\App\Game\Board $ownBoard, array $allShips): void
    {
        $this->shipPlacementStrategy->placeShips($ownBoard, $allShips);
    }


    public function shoot(\App\Game\Board $opponentBoard): void
    {
        $lastShotResult = null;
        do {
            $position = $this->shotFiringStrategy->determineNextShot(
                $opponentBoard->getReceivedShots(),
                $lastShotResult
            );
            $lastShotResult = $opponentBoard->receiveShot($position);
        } while ($lastShotResult->isHit);
    }
}
