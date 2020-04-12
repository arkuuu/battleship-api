<?php

declare(strict_types=1);

namespace App\ComputerPlayer\Strategy\Shooting;

interface ShootingStrategy
{

    public function determineNextShot(array $previousShots, ?\App\Game\ShotResult $lastShot): \App\Game\Position;
}
