<?php

declare(strict_types=1);

namespace App\Model;

class BoardModel extends Model
{

    /**
     * @var ReceivedShotModel
     */
    private $shotModel;

    /**
     * @var ShipPositionModel
     */
    private $shipPositionModel;

    /**
     * @var \App\Game\ShipFactory
     */
    private $shipFactory;


    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct(
        ReceivedShotModel $shotModel,
        ShipPositionModel $shipPositionModel,
        \App\Game\ShipFactory $shipFactory
    ) {
        $this->shipPositionModel = $shipPositionModel;
        $this->shotModel = $shotModel;
        $this->shipFactory = $shipFactory;
    }


    public function load(int $gameId, string $player): \App\Game\Board
    {
        $board = new \App\Game\Board();
        $this->shipPositionModel->load($gameId, $player, $board);
        $this->shotModel->load($gameId, $player, $board);

        return $board;
    }


    public function save(int $gameId, string $player, \App\Game\Board $board): void
    {
        $this->shipPositionModel->save($gameId, $player, $board);
        $this->shotModel->save($gameId, $player, $board->getReceivedShots());
    }
}
