<pre style="line-height: 20px"><?php

$start = microtime(1);

require "v2.php";
require "board.php";

require_once "ai/RandomAI.php";
require_once "ai/TurnScoreAi.php";

function play($p, $print = false) {
    $turn = null;
    $b = new Board();
    $r = 0;

    if($print) echo $b . "<hr>";

    do {
        $last = $turn;
        $current = $p[$r++%2];
        $turn = $current->turn($b);

        if($turn) {
            $b->apply($turn);
            if($print) echo $b . "<hr>";
        }
    } while($turn || $last);

    return $b->status();
}

$p = [
    new RandomAI(Board::PLAYER_ONE),
    new TurnScoreAi(Board::PLAYER_TWO)
];

$s = [
    'random' => 0,
    'score' => 0,
    'draw' => 0
];

for($i = 0; $i < 100; $i++) {
    $r = play($p);
    $p = array_reverse($p);

    if($r[Board::PLAYER_ONE] == $r[Board::PLAYER_TWO])
        $s['draw']++;
    else if($r[Board::PLAYER_ONE] > $r[Board::PLAYER_TWO])
        $s['random']++;
    else
        $s['score']++;
}

var_dump($s);


echo "\n\nCompleted in: " . (microtime(1) - $start) . " seconds";


