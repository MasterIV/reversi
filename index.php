<?php

session_start();

require_once "v2.php";
require_once "board.php";
require_once "ai/RandomAI.php";

if (isset($_GET['reset']))
    $_SESSION['board'] = null;

$ai = new RandomAI(Board::PLAYER_TWO);
$board = $_SESSION['board'] ? Board::read($_SESSION['board']) : new Board();
$error = $winner = null;

if (!empty($_POST['turn'])) {
    try {
        $board = $board->turn(Board::PLAYER_ONE, new V2($_POST['turn']['x'], $_POST['turn']['y']));

        do {
            $turn = $ai->turn($board);
            if ($turn) $board->apply($turn);
            $pt = $board->possibleTurns(Board::PLAYER_ONE);
        } while (!$pt && $turn);

        // nobody can turn any more
        if (!$pt && !$turn) {
            $result = $board->status();

            if ($result[Board::PLAYER_ONE] == $result[Board::PLAYER_TWO])
                $winner = 'draw';
            else if ($result[Board::PLAYER_ONE] > $result[Board::PLAYER_TWO])
                $winner = 'player';
            else
                $winner = 'computer';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include 'template.php';
$_SESSION['board'] = $winner ? null : (string)$board;