<?php

declare(strict_types=1);

namespace App\Game;

class PlacedShip
{

    /**
     * @var Ship
     */
    private $ship;

    /**
     * @var Position
     */
    private $position;

    /**
     * @var string
     */
    private $direction;


    public function __construct(Ship $ship, Position $position, string $direction)
    {
        $this->ship = $ship;
        $this->position = $position;
        $this->direction = $direction;
    }


    public function getShip(): Ship
    {
        return $this->ship;
    }


    public function getPosition(): Position
    {
        return $this->position;
    }


    public function getDirection(): string
    {
        return $this->direction;
    }
}
