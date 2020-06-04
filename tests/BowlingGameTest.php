<?php
use PF\BowlingGame;
use PF\Exceptions\InvalidRollScoreException;
use PF\Exceptions\InvalidTotalRollCountException;
use PHPUnit\Framework\TestCase;

class BowlingGameTest extends TestCase
{
    public function testGetScore_withAllZeros_returnsScoreZero()
    {
        // set up
        $game = new BowlingGame();
        for ($i = 0; $i < 20; $i++) {
            $game->roll(0);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(0, $score);
    }
    public function testGetScore_withAllOnes_returnsScore20()
    {
        // set up
        $game = new BowlingGame();
        for ($i = 0; $i < 20; $i++) {
            $game->roll(1);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(20, $score);
    }
    public function testGetScore_withASpare_returnsScoreWithSpareBonus()
    {
        // set up
        $game = new BowlingGame();
        $game->roll(2);
        $game->roll(8);
        $game->roll(5);
        // 2 + 8 + 5 (bonus) + 5 +17
        for ($i = 0; $i < 17; $i++) {
            $game->roll(1);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(37, $score);
    }
    public function testGetScore_withAStrike_returnsScoreWithStrikeBonus()
    {
        // set up
        $game = new BowlingGame();
        $game->roll(10);
        $game->roll(3);
        $game->roll(5);
        // 10 + 3 + 5 + 3 + 5 + 16
        for ($i = 0; $i < 16; $i++) {
            $game->roll(1);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(42, $score);
    }
    public function testGetScore_withPerfectGame_returns300()
    {
        // set up
        $game = new BowlingGame();
        for ($i = 0; $i < 12; $i++) {
            $game->roll(10);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(300, $score);
    }

    public function testGetScore_withAStrikeAndSpare_returnsScoreWithStrikeSpareBonus()
    {
        // set up
        $game = new BowlingGame();
        $game->roll(1);
        $game->roll(9);
        $game->roll(7);
        $game->roll(3);
        $game->roll(10);
        
        for($i = 0; $i < 14; $i++)
        {
            $game->roll(0);
        }
        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(47, $score);
    }

    public function testRoll_withNegativeScore_throwsException()
    {
        // set up
        $game = new BowlingGame();

        // assert
        $this->expectException(InvalidRollScoreException::class);

        // test
        $game->roll(-1);
    }

    public function testRoll_withTooLargeScore_throwsException()
    {
        // set up
        $game = new BowlingGame();

        // assert
        $this->expectException(InvalidRollScoreException::class);

        // test
        $game->roll(11);
    }

    public function testGetScore_withTooManyRolls_throwsException()
    {
        // set up
        $game = new BowlingGame();

        // assert
        $this->expectException(InvalidTotalRollCountException::class);

        // test
        for($i = 0; $i < 21; $i++)
        {
            $game->roll(0);
        }

        $game->getScore();
    }

    public function testGetScore_withMissingRolls_throwsException()
    {
        // set up
        $game = new BowlingGame();

        // assert
        $this->expectException(InvalidTotalRollCountException::class);

        // test
        $game->roll(0);

        $game->getScore();
    }

    public function tetsGetScore_withLastFrameSpareAndStrike_returnsCorrectScore()
    {
        // set up
        $game = new BowlingGame();
        for ($i = 0; $i < 18; $i++) {
            $game->roll(0);
        }
        $game->roll(1);
        $game->roll(9);
        $game->roll(10);

        // test
        $score = $game->getScore();
        // assert
        self::assertEquals(20, $score);
    }
}