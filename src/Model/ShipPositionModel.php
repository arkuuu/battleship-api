<?php

declare(strict_types=1);

namespace App\Model;

class ShipPositionModel extends Model
{

    /**
     * @var \App\Game\ShipFactory
     */
    private $factory;


    public function __construct(\App\Game\ShipFactory $factory, \App\Database $db)
    {
        $this->factory = $factory;
        parent::__construct($db);
    }


    public function load(int $gameId, string $player, \App\Game\Board $board): void
    {
        $this->db->query(
            'SELECT ship, `row`, `column`, direction
                FROM ship_position 
                WHERE game_id = :game_id AND player = :player',
            [
                'game_id' => $gameId,
                'player'  => $player,
            ]
        );

        $rows = $this->db->fetchAll();
        foreach ($rows as $row) {
            $ship = $this->factory->build($row['ship']);
            $position = new \App\Game\Position(
                (int)$row['row'],
                (int)$row['column']
            );
            $board->placeShip($ship, $position, $row['direction']);
        }
    }


    public function save(int $gameId, string $player, \App\Game\Board $board): void
    {
        $this->delete($gameId, $player);

        foreach ($board->getPlacedShips() as $placedShip) {
            $this->db->query(
                'INSERT INTO ship_position (game_id, player, ship, `row`, `column`, direction)
                    VALUES (:game_id, :player, :ship, :row, :column, :direction)',
                [
                    'game_id'   => $gameId,
                    'player'    => $player,
                    'ship'      => $placedShip->getShip()->getType(),
                    'row'       => $placedShip->getPosition()->getRow(),
                    'column'    => $placedShip->getPosition()->getCol(),
                    'direction' => $placedShip->getDirection(),
                ]
            );
        }
    }


    public function delete(int $gameId, string $player): void
    {
        $this->db->query(
            'DELETE FROM ship_position 
                WHERE game_id = :game_id AND player = :player',
            [
                'game_id' => $gameId,
                'player'  => $player,
            ]
        );
    }
}
