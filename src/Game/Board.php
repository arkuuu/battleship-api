<?php

declare(strict_types=1);

namespace App\Game;

class Board
{

    public const SIZE = 10;
    public const DIRECTION_HORIZONTAL = 'horizontal';
    public const DIRECTION_VERTICAL = 'vertical';

    /**
     * @var array
     */
    private $battleground = [];

    /**
     * @var array
     */
    private $shipHealth = [];

    /**
     * @var array
     */
    private $receivedShots = [];

    /**
     * @var PlacedShip[]
     */
    private $placedShips = [];


    public function placeShip(Ship $ship, Position $start, string $direction): void
    {
        if ($this->hasShip($ship->getType())) {
            throw new \App\Exception\ShipAlreadyPlacedException();
        }

        for ($i = 0; $i < $ship->getLength(); $i++) {
            if ($direction === self::DIRECTION_HORIZONTAL) {
                $position = new Position(
                    $start->getRow(),
                    $start->getCol() + $i
                );
            } elseif ($direction === self::DIRECTION_VERTICAL) {
                $position = new Position(
                    $start->getRow() + $i,
                    $start->getCol()
                );
            } else {
                throw new \UnexpectedValueException();
            }

            if (!$this->isPositionOnBoard($position)) {
                throw new \App\Exception\InvalidBoardPositionException();
            }
            if ($this->hasShipAt($position)) {
                throw new \App\Exception\InvalidShipPositionException();
            }

            $this->battleground[$position->getRow()][$position->getCol()] = $ship->getType();
        }

        $this->setShipHealth($ship->getType(), $ship->getLength());
        $this->placedShips[] = new PlacedShip($ship, $start, $direction);
    }


    public function setShipHealth(string $shipType, int $health): void
    {
        $this->shipHealth[$shipType] = $health;
    }


    private function isPositionOnBoard(Position $pos): bool
    {
        return $pos->getRow() < self::SIZE
            && $pos->getCol() < self::SIZE;
    }


    private function hasShipAt(Position $pos): bool
    {
        return !empty($this->battleground[$pos->getRow()][$pos->getCol()]);
    }


    private function hasShip($shipType): bool
    {
        return isset($this->shipHealth[$shipType]);
    }


    public function receiveShot(Position $pos): ShotResult
    {
        if (!$this->isPositionOnBoard($pos)) {
            throw new \App\Exception\InvalidBoardPositionException();
        }
        if ($this->hasAlreadyReceivedShot($pos)) {
            throw new \App\Exception\ShotThereAlreadyException();
        }

        $this->saveReceivedShot($pos);

        return $this->determineShotResult($pos);
    }


    private function saveReceivedShot(Position $pos): void
    {
        $this->receivedShots[$pos->getRow()][$pos->getCol()] = true;
    }


    private function determineShotResult(Position $pos): ShotResult
    {
        $result = new ShotResult();

        if (!$this->hasShipAt($pos)) {
            $result->isHit = false;

            return $result;
        }

        $shipType = $this->battleground[$pos->getRow()][$pos->getCol()];
        $this->decreaseShipHealth($shipType);

        $result->isHit = true;
        $result->shipType = $shipType;
        $result->shipSunk = $this->hasShipSunken($shipType);

        return $result;
    }


    private function decreaseShipHealth(string $shipType): void
    {
        $this->shipHealth[$shipType]--;
    }


    private function hasShipSunken(string $shipType): bool
    {
        return $this->shipHealth[$shipType] === 0;
    }


    private function hasAlreadyReceivedShot(Position $pos): bool
    {
        return !empty($this->receivedShots[$pos->getRow()][$pos->getCol()]);
    }


    /**
     * @return PlacedShip[]
     */
    public function getPlacedShips(): array
    {
        return $this->placedShips;
    }


    public function getBattleground(): array
    {
        return $this->battleground;
    }


    public function getReceivedShots(): array
    {
        return $this->receivedShots;
    }


    public function allShipsSunken(): bool
    {
        foreach ($this->shipHealth as $health) {
            if ($health !== 0) {
                return false;
            }
        }

        return true;
    }
}
