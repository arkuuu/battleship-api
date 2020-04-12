<?php

declare(strict_types=1);

namespace App\Game;

class PositionTranslator
{

    public function translate(string $field): ?Position
    {
        if (empty($field)) {
            return null;
        }

        $letter = $field[0];
        $number = (int)$field[1];

        $letterMapping = range('A', 'Z');
        $row = array_search($letter, $letterMapping, true);
        $col = $number - 1;

        return new Position($row, $col);
    }
}
