<?php

declare(strict_types=1);

namespace Tests\Game;

class PositionTranslatorTest extends \PHPUnit\Framework\TestCase
{

    public function testTranslateEmptyFieldReturnsNull()
    {
        $this->assertNull(
            (new \App\Game\PositionTranslator())->translate('')
        );
    }

    /**
     * @dataProvider validFieldsProvider
     */
    public function testTranslate(string $input, int $expectedRow, int $expectedCol)
    {
        $translator = new \App\Game\PositionTranslator();
        $position = $translator->translate($input);

        $this->assertSame($expectedRow, $position->getRow());
        $this->assertSame($expectedCol, $position->getCol());
    }


    public function validFieldsProvider(): array
    {
        return [
            ['A1', 0, 0],
            ['A2', 0, 1],
            ['B1', 1, 0],
            ['D3', 3, 2],
        ];
    }
}
