<?php

declare(strict_types=1);

namespace Tests\Game;

class BoardTest extends \PHPUnit\Framework\TestCase
{

    public function testPlaceShipOutOfBattlegroundFails()
    {
        $this->expectException(\App\Exception\InvalidBoardPositionException::class);
        $board = new \App\Game\Board();
        $board->placeShip(
            new \App\Game\Ship('foo', 1),
            new \App\Game\Position(10, 10),
            \App\Game\Board::DIRECTION_HORIZONTAL
        );
    }


    public function testPlaceShipOnOtherShipFails()
    {
        $this->expectException(\App\Exception\InvalidShipPositionException::class);
        $board = new \App\Game\Board();
        $board->placeShip(
            new \App\Game\Ship('foo', 2),
            new \App\Game\Position(0, 0),
            \App\Game\Board::DIRECTION_HORIZONTAL
        );
        $board->placeShip(
            new \App\Game\Ship('bar', 2),
            new \App\Game\Position(0, 1),
            \App\Game\Board::DIRECTION_HORIZONTAL
        );
    }


    public function testPlaceSameShipTwiceFails()
    {
        $this->expectException(\App\Exception\ShipAlreadyPlacedException::class);
        $board = new \App\Game\Board();
        $board->placeShip(
            new \App\Game\Ship('foo', 2),
            new \App\Game\Position(0, 0),
            \App\Game\Board::DIRECTION_HORIZONTAL
        );
        $board->placeShip(
            new \App\Game\Ship('foo', 2),
            new \App\Game\Position(1, 0),
            \App\Game\Board::DIRECTION_HORIZONTAL
        );
    }


    public function testPlaceShipInvalidDirectionFails()
    {
        $this->expectException(\UnexpectedValueException::class);
        $board = new \App\Game\Board();
        $board->placeShip(
            new \App\Game\Ship('foo', 2),
            new \App\Game\Position(0, 0),
            'diagonal'
        );
    }


    public function testPlaceShipHorizontally()
    {
        $board = new \App\Game\Board();
        $board->placeShip(
            new \App\Game\Ship('foo', 3),
            new \App\Game\Position(0, 0),
            \App\Game\Board::DIRECTION_HORIZONTAL
        );

        $this->assertSame(
            [
                0 => [
                    0 => 'foo',
                    1 => 'foo',
                    2 => 'foo',
                ],
            ],
            $board->getBattleground()
        );
    }


    public function testPlaceShipVertically()
    {
        $board = new \App\Game\Board();
        $board->placeShip(
            new \App\Game\Ship('foo', 3),
            new \App\Game\Position(0, 0),
            \App\Game\Board::DIRECTION_VERTICAL
        );

        $this->assertSame(
            [
                0 => [0 => 'foo'],
                1 => [0 => 'foo'],
                2 => [0 => 'foo'],
            ],
            $board->getBattleground()
        );
    }


    public function testReceiveShotOutOfBattlegroundFails()
    {
        $this->expectException(\App\Exception\InvalidBoardPositionException::class);
        $board = new \App\Game\Board();
        $board->receiveShot(new \App\Game\Position(10, 10));
    }


    public function testReceiveSameShotTwiceFails()
    {
        $this->expectException(\App\Exception\ShotThereAlreadyException::class);
        $board = new \App\Game\Board();
        $board->receiveShot(new \App\Game\Position(0, 0));
        $board->receiveShot(new \App\Game\Position(0, 0));
    }


    public function testReceiveShotMiss()
    {
        $board = new \App\Game\Board();
        $board->placeShip(
            new \App\Game\Ship('foo', 2),
            new \App\Game\Position(2, 2),
            \App\Game\Board::DIRECTION_HORIZONTAL
        );
        $shotResult = $board->receiveShot(
            new \App\Game\Position(0, 0)
        );

        $this->assertFalse($shotResult->isHit);
        $this->assertNull($shotResult->shipType);
        $this->assertNull($shotResult->shipSunk);
    }


    public function testReceiveShotHit()
    {
        $board = new \App\Game\Board();
        $board->placeShip(
            new \App\Game\Ship('foo', 2),
            new \App\Game\Position(2, 1),
            \App\Game\Board::DIRECTION_HORIZONTAL
        );
        $shotResult = $board->receiveShot(
            new \App\Game\Position(2, 1)
        );

        $this->assertTrue($shotResult->isHit);
        $this->assertSame('foo', $shotResult->shipType);
        $this->assertFalse($shotResult->shipSunk);
    }


    public function testReceiveShotShipSunk()
    {
        $board = new \App\Game\Board();
        $board->placeShip(
            new \App\Game\Ship('foo', 2),
            new \App\Game\Position(2, 2),
            \App\Game\Board::DIRECTION_HORIZONTAL
        );
        $shotResult = $board->receiveShot(
            new \App\Game\Position(2, 2)
        );
        $this->assertTrue($shotResult->isHit);
        $this->assertSame('foo', $shotResult->shipType);
        $this->assertFalse($shotResult->shipSunk);

        $shotResult = $board->receiveShot(
            new \App\Game\Position(2, 3)
        );
        $this->assertTrue($shotResult->isHit);
        $this->assertSame('foo', $shotResult->shipType);
        $this->assertTrue($shotResult->shipSunk);
    }


    public function testGetReceivedShots()
    {
        $board = new \App\Game\Board();
        $this->assertSame(
            [],
            $board->getReceivedShots()
        );

        $board->receiveShot(new \App\Game\Position(2, 2));
        $this->assertSame(
            [2 => [2 => true]],
            $board->getReceivedShots()
        );

        $board->receiveShot(new \App\Game\Position(2, 3));
        $this->assertSame(
            [2 => [2 => true, 3 => true]],
            $board->getReceivedShots()
        );
    }


    public function testGetPlacedShips()
    {
        $board = new \App\Game\Board();
        $this->assertSame([], $board->getPlacedShips());

        $ship = new \App\Game\Ship('test', 1);
        $position = new \App\Game\Position(0, 0);
        $direction = \App\Game\Board::DIRECTION_HORIZONTAL;
        $board->placeShip($ship, $position, $direction);

        $this->assertEquals(
            [new \App\Game\PlacedShip($ship, $position, $direction)],
            $board->getPlacedShips()
        );
    }


    public function testAllShipsSunken()
    {
        $board = new \App\Game\Board();
        $board->setShipHealth('foo', 5);
        $board->setShipHealth('foobar', 1);
        $this->assertFalse($board->allShipsSunken());

        $board->setShipHealth('foobar', 0);
        $this->assertFalse($board->allShipsSunken());

        $board->setShipHealth('foo', 0);
        $this->assertTrue($board->allShipsSunken());
    }
}
