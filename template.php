<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reversi</title>

    <style>
        body {
            font-family: sans-serif;
        }

        .row {
            display: block;
            margin: 0;
            padding: 0;
        }

        .field {
            box-shadow: #040 2px 2px 4px inset ;
            display: inline-block;
            border: 1px solid black;
            background: darkgreen;
            padding: 10px;
        }

        .field > button {
            width: 50px;
            height: 50px;
            display: block;
            border: none;
        }

        .free button {
            cursor: pointer;
            background: transparent;
        }

        .playerx button {
            border-radius: 100%;
            background: black;
        }

        .playero button {
            border-radius: 100%;
            background: white;
        }

        .alert {
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
        }

        .error {
            border: 1px solid #A00;
            color: #A00;
            background: #FAA;
        }

        .success {
            border: 1px solid #0A0;
            color: #0A0;
            background: #AFA;
        }
    </style>
</head>
<body>
    <?php
        if($error)
            echo '<div class="alert error">'.$error.'</div>';
        if($winner)
            echo '<div class="alert success">The winner is: '.$winner.'. <a href="?">Play again!</a></div>';
    ?>

    <p>You are black.</p>

    <div><?php

        foreach(explode("\n", $board) as $y => $l) {
            echo '<div class="row">';

            foreach(explode(" ", $l) as $x => $f) {
                if($f == '_') {
                    echo '<form class="field free" method="post" action="#">
                            <input type="hidden" name="turn[x]" value="'.$x.'" /> 
                            <input type="hidden" name="turn[y]" value="'.$y.'" /> 
                            <button type="submit"> </button>
                          </form>';
                } else {
                   echo '<div class="field player'.$f.'"><button /></div>';
                }
            }

            echo "</div>";
        }
    ?>
    </div>
</body>
</html>