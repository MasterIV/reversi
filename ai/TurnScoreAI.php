<?php

require_once 'ReversiAI.php';

class TurnScoreAI implements ReversiAI {
    const BORDER = 4;

    private $player;
    private $enemy;

    public function __construct($player) {
        $this->player = $player;
        $this->enemy = $player == Board::PLAYER_ONE
            ? Board::PLAYER_TWO
            : Board::PLAYER_ONE;
    }


    function turn(Board $board) {
        $turns = $board->possibleTurns($this->player);
        if(!$turns) return null;

        $max = 0;
        $turn = null;

        foreach($turns as $t) {
            $flips = count($t->tokens);

            $p = clone $board;
            $p->apply($t);

            $enemy_flips = 0;
            $enemy_turns = $p->possibleTurns($this->enemy);
            foreach ($enemy_turns as $et) {
                $tf = count($et->tokens);
                if($tf > $enemy_flips)
                    $enemy_flips = $tf;
            }

            $border = 1;
            if($t->pos->x == 0 || $t->pos->x == 7)
                $border *= self::BORDER;
            if($t->pos->y == 0 || $t->pos->y == 7)
                $border *= self::BORDER;

            $score = $flips - $enemy_flips + $border;

            if($score > $max) {
                $max = $score;
                $turn = $t;
            }
        }

        return $turn;
    }
}