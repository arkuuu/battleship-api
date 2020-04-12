<?php

declare(strict_types=1);

namespace App\ComputerPlayer\Strategy\Shooting;

class Random implements ShootingStrategy
{

    public function determineNextShot(array $previousShots, ?\App\Game\ShotResult $lastShot): \App\Game\Position
    {
        do {
            $row = rand(0, \App\Game\Board::SIZE - 1);
            $col = rand(0, \App\Game\Board::SIZE - 1);
        } while (!empty($previousShots[$row][$col]));

        return new \App\Game\Position($row, $col);
    }
}
