<?php

function drawRepeated($draw, $x, $y = NULL, $coords = NULL){
    $result = "";
    for ($i = 0; $i < $x; $i++) {
        if ($coords){
            $draw = "|   |";
            if (in_array([$i, $y], $coords))
                $draw = "| X |";
        }

        if (substr($result, -1) == substr($draw, 0, 1)) {
            $result .= substr($draw, 1);
        }
        else {
            $result .= $draw;
        }
    }
    return $result;
}

function colle(int $x, int $y, array $coords) : void {
    define("COORDS", $coords);
    if ($x > 0 & $y > 0) {
        for ($i = 0; $i < $y; $i++) {
            echo drawRepeated("+–––+", $x) . PHP_EOL;
            echo drawRepeated("|   |", $x, $i, $coords) . PHP_EOL;
        }
        echo drawRepeated("+–––+", $x) . PHP_EOL;

    }
}

colle(4,5, [ [0,0], [0, 1], [0,4]]);
//var_dump(COORDS);