<?php
session_start();

if (isset($_SESSION['matchID']['roundCount'])) {
    $_SESSION['matchID']['roundCount']++;
} else {
    $_SESSION['matchID']['roundCount'] = 0;
}
function generate_coords() {
    $x = rand(1, 10);
    if($x == 10 ) $y = 0;
    else{
        $y = rand(1, 10 - $x);
    }
    return [ $x, $y];
}

$locationFound = false;
$hitTree = false;

$data = json_decode(file_get_contents('php://input'), true);
file_put_contents("/tmp/apples.txt",print_r($data, 1), FILE_APPEND);

while (!$locationFound) {
    $coords = generate_coords();

    foreach( $data['field']['trees'] as $tree) {
        if($coords['x'] == $tree['x'] && $coords['y'] == $tree['y']) {
            $hitTree = true;
            break;
        }
    }
    if(!$hitTree) $locationFound = true;
    $hitTree = false;
}

function moveOrPlant($coords, $data){
    $string = $_SESSION['matchID']['roundCount'] % 2 == 0 ? 'plant' : 'move';
    //if($coords['x'] == $data['x'] && $coords['x'] == $data['x']) $string = 'move';
    return $string;
}

$action = [
    'action' => moveOrPlant($coords, $data),
    'dest' => [
        'x' => $coords['x'],
        'y' => $coords['y']
    ]
];

echo json_encode($action, true);


