<?php
require __DIR__ . '/../vendor/autoload.php';
require_once 'env_loader.php';
require 'db_connection.php';
require 'json_response.php';
require 'jwt.php';
require 'utils.php';

$userId = null;
$gameId = null;
$lang = 'en';
$money = 0;
$homeUrl = null;

# Request Validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? null;
    $gameId = $_POST['gameId'] ?? null;
    $lang = $_POST['lang'] ?? 'en';
    $money = $_POST['money'] ?? 0.0;
    $homeUrl = $_POST['homeUrl'] ?? null;
    if (!$userId || $gameId !== $_ENV['GAME_ID'] || !$homeUrl) {
        errorResponse('Missing paramaters');
    }
} else {
    errorResponse('Not Allowed Method!');
}

$apiKey = $_ENV['X_API_KEY'];
$headers = getallheaders();
if (!isset($headers['X-API-Key']) || $headers['X-API-Key'] !== $apiKey) {
    errorResponse('Invalid API Key');
}

// Generate token
$token = generateToken($userId, $gameId, $lang, $money, $homeUrl);
$gameRound = generateRandomString(26);
// Upsert to db
try {
    upsertTable($userId, $gameId, $lang, $money, $homeUrl, $token, $gameRound);
    // Send token to client
    successReponse(['token' => $token]);
} catch (\Throwable $th) {
    errorResponse($th);
}
?>