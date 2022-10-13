<?php

function drawRepeated($draw, $count){
    $result = "";
    for ($i = 0; $i < $count; $i++) {
        if (substr($result, -1) == substr($draw, 0, 1)) {
            $result .= substr($draw, 1);
        }
        else {
            $result .= $draw;
        }
    }
    return $result;
}

function colle(int $x, int $y) : void {
    if ($x > 0 & $y > 0) {
        for ($i = 0; $i < $y; $i++) {
            echo drawRepeated("+–––+", $x) . PHP_EOL;
            echo drawRepeated("|   |", $x) . PHP_EOL;
        }
        echo drawRepeated("+–––+", $x) . PHP_EOL;

    }
}

//colle(1,2);