<?php

declare(strict_types=1);

namespace App\Model;

class GameModel extends Model
{

    private $boardModel;


    public function __construct(BoardModel $boardModel, \App\Database $db)
    {
        $this->boardModel = $boardModel;
        parent::__construct($db);
    }


    public function findByToken(string $token): ?\App\Game\Game
    {
        $this->db->query(
            'SELECT id, token, state FROM game WHERE token = :token',
            ['token' => $token]
        );

        $row = $this->db->fetchSingle();
        if (empty($row)) {
            return null;
        }

        return $this->buildGameFromRow($row);
    }


    private function buildGameFromRow(array $row): \App\Game\Game
    {
        $game = new \App\Game\Game();
        $game->setId((int)$row['id']);
        $game->setToken($row['token']);
        $game->setState($row['state']);
        $game->setPlayerBoard(
            $this->boardModel->load(
                $game->getId(),
                \App\Game\Player::PLAYER
            )
        );
        $game->setComputerBoard(
            $this->boardModel->load(
                $game->getId(),
                \App\Game\Player::COMPUTER
            )
        );

        return $game;
    }


    public function save(\App\Game\Game $game): void
    {
        $this->db->query(
            'INSERT INTO game (id, token, state)
                VALUES (:id, :token, :state)
                ON DUPLICATE KEY UPDATE state = :state',
            [
                'id'    => $game->getId() ?? null,
                'token' => $game->getToken(),
                'state' => $game->getState(),
            ]
        );
        if (!$game->getId()) {
            $game->setId($this->db->lastInsertId());
        }

        $this->boardModel->save($game->getId(), 'player', $game->getPlayerBoard());
        $this->boardModel->save($game->getId(), 'computer', $game->getComputerBoard());
    }
}
