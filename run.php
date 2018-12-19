<pre style="line-height: 20px"><?php

$start = microtime(1);

require "v2.php";
require "board.php";

$b = new Board();
$turn = 0;
$p = [Board::PLAYER_ONE, Board::PLAYER_TWO];

echo $b;
echo "<hr>";

while ($turns = $b->possibleTurns($p[$turn%2])) {
    $b = $b->turn($p[$turn++%2], $turns[rand(0, count($turns)-1)][0]);
    echo $b . "<hr>";
};

var_dump($b->status());
echo "<hr>Completed in: " . (microtime(1) - $start) . " seconds";




