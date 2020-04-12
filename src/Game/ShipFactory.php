<?php

declare(strict_types=1);

namespace App\Game;

class ShipFactory
{

    public const CARRIER = 'carrier';
    public const BATTLESHIP = 'battleship';
    public const CRUISER = 'cruiser';
    public const SUBMARINE = 'submarine';
    public const DESTROYER = 'destroyer';


    public function build(string $type): Ship
    {
        switch ($type) {
            case self::CARRIER:
                return new Ship($type, 5);
            case self::BATTLESHIP:
                return new Ship($type, 4);
            case self::CRUISER:
            case self::SUBMARINE:
                return new Ship($type, 3);
            case self::DESTROYER:
                return new Ship($type, 2);
            default:
                throw new \InvalidArgumentException();
        }
    }


    /**
     * @return Ship[]
     */
    public function buildAll(): array
    {
        $reflection = new \ReflectionClass($this);
        $shipsToBuild = $reflection->getConstants();

        $ships = [];
        foreach ($shipsToBuild as $ship) {
            $ships[] = $this->build($ship);
        }

        return $ships;
    }
}
