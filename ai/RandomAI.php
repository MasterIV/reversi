<?php

require_once 'ReversiAI.php';

class RandomAI implements ReversiAI {
    private $player;

    public function __construct($player) {
        $this->player = $player;
    }

    function turn(Board $board) {
        $turns = $board->possibleTurns($this->player);
        return count($turns) ? $turns[rand(0, count($turns)-1)] : null;
    }
}