<?php
namespace PF;
use PF\Exceptions\InvalidRollScoreException;
use PF\Exceptions\InvalidTotalRollCountException;

class BowlingGame
{
    public const MAX_FRAMES = 10;

    private array $rolls = [];
    /**
     * @param int $score
     * @throws InvalidRollScoreException
     */
    public function roll(int $score): void
    {
        if($score < 0)
        {
            throw new InvalidRollScoreException('Minimum roll score is 0, provided score: '. $score);
        }

        if($score > 10)
        {
            throw new InvalidRollScoreException('Maximum roll score is 10, provided score: '. $score);
        }

        $this->rolls[] = $score;
    }

    /**
     * @return int
     * @throws InvalidTotalRollCountException
     */
    public function getScore(): int
    {
        $score = 0;
        $roll = 0;

        for ($frame = 1; $frame <= self::MAX_FRAMES ; $frame++) {
            if ($this->isStrike($roll)) {
                $score += $this->getStrikeScore($roll);
                // On last frame count in last 2 + current rolls
                $roll = $this->isLastFrame($frame) ? $roll + 3 : $roll + 1;

                continue;
            }
            if ($this->isSpare($roll)) {
                $score += $this->getSpareBonus($roll);
            }
            $score += $this->getFrameAmount($roll);
            $roll += 2;
        }

        if(count($this->rolls) > $roll)
        {
            throw new InvalidTotalRollCountException('Too many rolls ('.count($this->rolls).' > '.$roll.')');
        }

        return $score;
    }

    /**
     * @param int $roll
     * @return int
     * @throws InvalidTotalRollCountException
     */
    private function getFrameAmount(int $roll): int
    {
        return $this->getRollScore($roll) + $this->getRollScore($roll + 1);
    }

    /**
     * @param int $roll
     * @return bool
     * @throws InvalidTotalRollCountException
     */
    private function isSpare(int $roll): bool
    {
        return $this->getFrameAmount($roll) === 10;
    }

    /**
     * @param int $roll
     * @return int
     * @throws InvalidTotalRollCountException
     */
    private function getSpareBonus(int $roll): int
    {
        return $this->getRollScore($roll + 2);
    }

    /**
     * @param int $roll
     * @return bool
     * @throws InvalidTotalRollCountException
     */
    private function isStrike(int $roll): bool
    {
        return $this->getRollScore($roll) === 10;
    }

    /**
     * @param int $roll
     * @return int
     * @throws InvalidTotalRollCountException
     */
    private function getStrikeScore(int $roll): int
    {
        return 10 + $this->getRollScore($roll + 1) + $this->getRollScore($roll + 2);
    }

    /**
     * @param int $roll
     * @return mixed
     * @throws InvalidTotalRollCountException
     */
    public function getRollScore(int $roll): int
    {
        if(!isset($this->rolls[$roll]))
        {
            throw new InvalidTotalRollCountException('Lacking rolls! Missing required roll:' . $roll);
        }

        return $this->rolls[$roll];
    }

    public function isLastFrame($frame): bool
    {
        return $frame === self::MAX_FRAMES;
    }
}