<?php

interface ReversiAI {
    function __construct($player);
    function turn(Board $board);
}