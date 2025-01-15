<?php
header('Content-Type: application/json');

$userId = $_POST['userId'] ?? null;
$gameId = $_POST['gameId'] ?? null;
$lang = $_POST['lang'] ?? 'en';
$money = $_POST['money'] ?? 0.0;
$home_url = $_POST['home_url'] ?? null;

$logData = [
    'userId' => $userId,
    'gameId' => $gameId,
    'lang' => $lang,
    'money' => $money,
    'home_url' => $home_url
];
file_put_contents('game_launch_log.txt', json_encode($logData) . PHP_EOL, FILE_APPEND);

$gameUrl = "game.php?userId=$userId&gameId=$gameId&lang=$lang&money=$money&home_url=$home_url";
echo json_encode(['success' => true, 'gameUrl' => $gameUrl]);
?>
