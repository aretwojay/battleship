<?php

class Player{
    private static $id = 1;

    public function __construct(){
        $this->_id = self::$id;
        $this->_add = 0;
        $this->_boats = [];
        $this->score = 0;
        self::$id++;
    }

    public function IncrementAdd($boat){
        array_push($this->_boats, $boat); 
        $this->_add++;
    }

    public function setScore(){
        $this->score++;
    }

    public function getScore(){
        return $this->score;
    }

    public function getBoats(){
        return $this->_boats;
    }

    public function getAdd(){
        return $this->_add;
    }

    public function __toString(){
        return "Player " . $this->_id;
    }

}

function get_numerics($array){
    $result = [];
    foreach ($array as $key => $value){
        if (is_numeric($value)){
            array_push($result, (int)$value);
        }
    }
    return $result;
}

function actions($prompt, $delimiter) {
    global $all_coords, $players, $phase, $turn_player; 

    $array = explode($delimiter, $prompt);
    $str_coords = preg_replace('/\s+/', '', $array[1]);
    $coords = get_numerics(str_split($str_coords));

    if (count($coords) == 0){
        return;
    }

    switch ($delimiter) {
        case "query":
            $turn_player == 0 ? $opps = 1 : $opps = 0; 

            if (in_array($coords, $players[$opps]->getBoats())) {
                $players[$turn_player]->setScore();
                return $players[$turn_player]. ", you touched a boat of player " . ($opps+1) .  " !" . PHP_EOL;
            }
            return $players[$turn_player]. ", you didn't touch anything." . PHP_EOL;
            break;
        case "add":
            if (in_array($coords, $all_coords)) {
                return "A cross already exists at this location";
            }
            array_push($all_coords, $coords);
            $players[$turn_player]->IncrementAdd($coords);

            if ($players[$turn_player]->getAdd() == 2 && $turn_player == 0){
                $turn_player = 1;
            }
            else if ($players[$turn_player]->getAdd() == 2 && $turn_player == 1){
                $phase = 1;
            }
            break;
        case "remove":
            if (!in_array($coords, $all_coords)) {
                return "No cross exists at this location";
            }
            foreach ($all_coords as $key => $value){
                if ($value == $coords) {
                    unset($all_coords[$key]);
                }
            }  
            break;          
    }
}

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

function game_phase($phase, $turn){
    global $players;

    var_dump($players[$turn]->getAdd());
    switch ($phase) {
        case 0:
            if ($players[$turn]->getAdd() == 0)
                return $players[$turn]. ", place your 2 ships :" . PHP_EOL;
            break;
        case 1:
            return $players[$turn]. ", launch your attack :" . PHP_EOL;
            break;
        case 2:
            return $players[$turn]. " win !!";
    }
}

function colle(int $x, int $y, array $coords = null) : void {
    global $row, $col, $all_coords; 
    $row = $x;
    $col = $y;
    $all_coords = $coords;

    if ($x > 0 & $y > 0) {
        for ($i = 0; $i < $y; $i++) {
            echo drawRepeated("+–––+", $x) . PHP_EOL;
            echo drawRepeated("|   |", $x, $i, $coords) . PHP_EOL;
        }
        echo drawRepeated("+–––+", $x) . PHP_EOL;
    }
}

$row;
$col;
$all_coords = [];

colle(4, 4, []);

if ($row > 0 && $col > 0 && gettype($all_coords) == "array") {
    $player1 = new Player();
    $player2 = new Player();
    $players = [$player1, $player2];

    $phase = 0;
    $turn_player = 0;

    do {

        if ($phase == 1 && $turn_player == 2){
            $turn_player = 0;
        }

        if ($players[0]->getScore() == 2 || $players[1]->getScore() == 2){
            $phase = 2;
            echo game_phase($phase, $turn_player);
            exit();
        }
        echo game_phase($phase, $turn_player);
        
        $prompt = readline($players[$turn_player] . " $> ");
        
        switch ($prompt){
            case "":
                break;
            case str_starts_with($prompt, "query"):
                echo actions($prompt, "query") . PHP_EOL;
                break;
            case str_starts_with($prompt, "add"):
                echo actions($prompt, "add"). PHP_EOL;
                break;
            case str_starts_with($prompt, "remove"):
                echo actions($prompt, "remove"). PHP_EOL;
                break;
            case "display":
                colle($row, $col, $all_coords);
                break;
        }
        if ($phase == 1) {
            $turn_player++;
        }

    } while ($prompt != "exit");
}