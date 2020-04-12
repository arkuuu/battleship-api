<?php

declare(strict_types=1);

namespace App\Game;

class ShotResult
{

    /**
     * @var bool
     */
    public $isHit = false;

    /**
     * @var string|null
     */
    public $shipType = null;

    /**
     * @var bool|null
     */
    public $shipSunk = null;
}
