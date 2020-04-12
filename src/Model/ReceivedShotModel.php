<?php

declare(strict_types=1);

namespace App\Model;

class ReceivedShotModel extends Model
{

    public function load(int $gameId, string $player, \App\Game\Board $board): void
    {
        $this->db->query(
            'SELECT `row`, `column`
                FROM received_shot 
                WHERE game_id = :game_id AND player = :player',
            [
                'game_id' => $gameId,
                'player'  => $player,
            ]
        );

        $shots = $this->db->fetchAll();
        foreach ($shots as [$row, $column]) {
            $board->receiveShot(new \App\Game\Position((int)$row, (int)$column));
        }
    }


    public function save(int $gameId, string $player, array $shots): void
    {
        $this->delete($gameId, $player);

        foreach ($shots as $row => $columns) {
            foreach ($columns as $col => $shot) {
                if ($shot) {
                    $this->db->query(
                        'INSERT INTO received_shot (game_id, player, `row`, `column`)
                VALUES (:game_id, :player, :row, :column)',
                        [
                            'game_id' => $gameId,
                            'player'  => $player,
                            'row'     => $row,
                            'column'  => $col,
                        ]
                    );
                }
            }
        };
    }


    public function delete(int $gameId, string $player): void
    {
        $this->db->query(
            'DELETE FROM received_shot 
                WHERE game_id = :game_id AND player = :player',
            [
                'game_id' => $gameId,
                'player'  => $player,
            ]
        );
    }
}
