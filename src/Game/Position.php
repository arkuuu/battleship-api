<?php

declare(strict_types=1);

namespace App\Game;

class Position
{

    /**
     * @var int
     */
    private $row;

    /**
     * @var int
     */
    private $col;


    public function __construct(int $row, int $col)
    {
        $this->row = $row;
        $this->col = $col;
    }


    public function getRow(): int
    {
        return $this->row;
    }


    public function getCol(): int
    {
        return $this->col;
    }
}
