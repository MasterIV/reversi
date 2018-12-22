<?php

class Turn {
    public $pos;
    public $tokens;
    public $player;

    public function __construct($player, V2 $pos, $tokens) {
        $this->pos = $pos;
        $this->tokens = $tokens;
        $this->player = $player;
    }

    public function all() {
        return array_merge($this->tokens, [$this->pos]);
    }
}

class Board {
    private $fields;

    const FREE = '_';
    const PLAYER_ONE = 'x';
    const PLAYER_TWO = 'o';

    function __construct() {
        $this->fields = array_fill(0, 8, array_fill(0, 8, self::FREE));
        $this->fields[4][3] = $this->fields[3][4] = self::PLAYER_ONE;
        $this->fields[4][4] = $this->fields[3][3] = self::PLAYER_TWO;
    }

    function __toString() {
        return implode("\n", array_map(function ($l) {
            return implode(" ", $l);
        }, $this->fields));
    }

    public static function read($str) {
        $board = new self();
        $board->fields = array_map(function ($l) {
            return explode(" ", $l);
        }, explode("\n", $str));
        return $board;
    }

    public function set(V2 $pos, $value) {
        $this->fields[$pos->y][$pos->x] = $value;
    }

    public function apply(Turn $turn) {
        foreach ($turn->all() as $t)
            $this->set($t, $turn->player);
    }

    public function turn($player, V2 $pos) {
        if (!$pos->valid())
            throw new Exception("Invalid position!");

        $turn = $this->getTurn($player, $pos);
        if (!$turn)
            throw new Exception("Invalid turn!");

        $result = clone $this;
        $result->apply($turn);
        return $result;
    }

    public function status() {
        $result = [
            self::FREE => 0,
            self::PLAYER_ONE => 0,
            self::PLAYER_TWO => 0
        ];

        for ($x = 0; $x < 8; $x++)
            for ($y = 0; $y < 8; $y++)
                $result[$this->fields[$y][$x]]++;

        return $result;
    }

    /** @return Turn[] */
    public function possibleTurns($player) {
        $turns = [];

        for ($x = 0; $x < 8; $x++)
            for ($y = 0; $y < 8; $y++) {
                $pos = new V2($x, $y);
                if ($turn = $this->getTurn($player, $pos))
                    $turns[] = $turn;
            }

        return $turns;
    }

    /** @return Turn|bool */
    public function getTurn($player, V2 $pos) {
        if ($this->fields[$pos->y][$pos->x] != self::FREE)
            return false;

        $enemy = $player == self::PLAYER_ONE ? self::PLAYER_TWO : self::PLAYER_ONE;
        $tokens = array_merge(
            $this->checkChanges($player, $enemy, $pos, new V2(0, 1)),
            $this->checkChanges($player, $enemy, $pos, new V2(0, -1)),
            $this->checkChanges($player, $enemy, $pos, new V2(1, 0)),
            $this->checkChanges($player, $enemy, $pos, new V2(-1, 0)),
            $this->checkChanges($player, $enemy, $pos, new V2(1, 1)),
            $this->checkChanges($player, $enemy, $pos, new V2(-1, 1)),
            $this->checkChanges($player, $enemy, $pos, new V2(1, -1)),
            $this->checkChanges($player, $enemy, $pos, new V2(-1, -1)));

        return count($tokens) ? new Turn($player, $pos, $tokens) : false;
    }

    private function checkChanges($player, $enemy, V2 $start, V2 $dir) {
        $tokens = [];

        for ($i = $start->sum($dir); $i->valid() && $this->fields[$i->y][$i->x] == $enemy; $i->add($dir))
            $tokens[] = clone $i;

        return $i->valid() && $this->fields[$i->y][$i->x] == $player ? $tokens : [];
    }
}
