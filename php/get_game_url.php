<?php
header('Content-Type: application/json');

$expectedApiKey = 'THISISTHEKEY';
$headers = getallheaders();
if (!isset($headers['X-API-Key']) || $headers['X-API-Key'] !== $expectedApiKey) {
    echo json_encode(['success' => false, 'message' => 'Invalid API Key']);
    exit;
}

$userId = $_POST['userId'];
$gameId = $_POST['gameId'];
$lang = $_POST['lang'];
$money = $_POST['money'];
$homeUrl = $_POST['home_url'];

$userBalance = 500.00;

if ($userBalance === null) {
    header("Location: $homeUrl");
    exit;
}

$launchUrl = "https://example.com/game?userId=$userId&gameId=$gameId&lang=$lang&session=" . uniqid();

echo json_encode(['success' => true, 'launchUrl' => $launchUrl]);
?>
