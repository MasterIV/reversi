<?php

session_start();

require "v2.php";
require "board.php";

if(isset($_GET['reset']))
    $_SESSION['board'] = null;

$board = $_SESSION['board'] ? Board::read($_SESSION['board']) : new Board();
$error = $winner = null;

if(!empty($_POST['turn'])) {
    try {
        $board = $board->turn(Board::PLAYER_ONE, new V2($_POST['turn']['x'], $_POST['turn']['y']));

        do {
            $turns = $board->possibleTurns(Board::PLAYER_TWO );
            if( $turns ) $board->apply($turns[rand(0, count($turns)-1)]);
            $pt = $board->possibleTurns(Board::PLAYER_TWO );
        } while(!$pt && $turns);

        // nobody can turn any more
        if(!$pt && !$turns) {
            $result = $board->status();

            if($result[Board::PLAYER_ONE] == $result[Board::PLAYER_TWO])
                $winner = 'draw';
            else if($result[Board::PLAYER_ONE] > $result[Board::PLAYER_TWO])
                $winner = 'player';
            else
                $winner = 'computer';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include 'template.php';
$_SESSION['board'] = $winner ? null : (string) $board;