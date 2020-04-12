<?php

declare(strict_types=1);

namespace App\Game;

class Ship
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $length;


    public function __construct(string $type, int $length)
    {
        $this->type = $type;
        $this->length = $length;
    }


    public function getType(): string
    {
        return $this->type;
    }


    public function getLength(): int
    {
        return $this->length;
    }
}
