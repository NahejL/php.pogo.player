<?php

//$a = new johnnyappleseed(2, false);

$data = json_decode(file_get_contents('php://input'), true);


interface brains {

}


class johnnyappleseed implements brains {
    public $field;
    public $jumpDistance, $log;

    public function __construct($jump = 2, $log = false) {
        $this->jumpDistance  = $jump;

        $this->log = $log;
        if ($log) {
            echo "TIMBER I'M A LOGGER";
            var_dump($this->log);
        }
    }

    public function work($field, $x, $y) {
        $action = [];
        $this->field = $field;
        //If there's no tree where I am, plant one.
        $found = false;
        $this->log("Is there a tree already at $x, $y?");
        foreach($field['trees'] as $tree) {
            if ($tree['position']['x'] == $x AND $tree['position']['y'] == $y) {
                $found = true;
                $this->log("There's a tree here!");
                break;
            }else {

            }
        }
        if ($found == false) {
            //plant tree
            $action['action'] = "plant";
            return $action;
        }


        //There's a tree here already, move.
        //We will check North, East, South, West in that order.
        if ($this->isSpotTreeFree($x, $y + $this->jumpDistance)) {
            $this->log("Go North");
            $action['action'] = "move";
            $action['dest'] = ['x' => $x, 'y' => $y + $this->jumpDistance];
            return $action;
        }else if ($this->isSpotTreeFree($x + $this->jumpDistance, $y)) {
            $this->log("Go East");
            $action['action'] = "move";
            $action['dest'] = ['x' => $x + $this->jumpDistance, 'y' => $y];
            return $action;
        }else if ($this->isSpotTreeFree($x, $y - $this->jumpDistance)) {
            $this->log("Go South");
            $action['action'] = "move";
            $action['dest'] = ['x' => $x, 'y' => $y - $this->jumpDistance];
            return $action;
        }else if ($this->isSpotTreeFree($x - $this->jumpDistance, $y)) {
            $this->log("Go West");
            $action['action'] = "move";
            $action['dest'] =  ['x' => $x - $this->jumpDistance, 'y' => $y];
            return $action;
        }

    }

    public function isSpotTreeFree($x, $y) {
        if (!$this->validMove($x, $y)) {
            $this->log("invalid move");
            return false;
        }
        foreach($this->field['trees'] as $tree) {
            if ($tree['position']['x'] == $x AND $tree['position']['y'] == $y) {
                return false;
            }
        }

        return true;
    }

    public function validMove($x, $y) {
        // var_dump($this->field['fieldSize']);
        //exit;
        if ($x < $this->field['fieldSize']['width'] && $y < $this->field['fieldSize']['height'] && $x >= 0 && $y >= 0) {
            return true;
        }
        return false;
    }

    public function log($message) {
        if ($this->log == false) {
            return;
        }
        if (is_string($message)) {
            echo $message;
        }else {
            var_dump($message);
        }
    }
}



$a = new johnnyappleseed();
$resp = $a->work($data['field'], $data['x'], $data['y']);
echo json_encode($resp, true);