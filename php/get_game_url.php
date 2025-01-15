<?php
header('Content-Type: application/json');

// Define the expected API key
$expectedApiKey = 'your_expected_api_key';

// Check for the API key in the headers
$headers = getallheaders();
if (!isset($headers['X-API-Key']) || $headers['X-API-Key'] !== $expectedApiKey) {
    echo json_encode(['success' => false, 'message' => 'Invalid API Key']);
    exit;
}

// Retrieve POST data
$userId = $_POST['userId'] ?? null;
$gameId = $_POST['gameId'] ?? null;
$lang = $_POST['lang'] ?? 'en';
$money = $_POST['money'] ?? 0.0;
$homeUrl = $_POST['home_url'] ?? null;

// Generate a unique launch URL
$uniqueId = uniqid();
$launchUrl = "game.php?userId=$userId&gameId=$gameId&lang=$lang&money=$money&home_url=$homeUrl&session=$uniqueId";

// Return the launch URL
echo json_encode(['success' => true, 'launchUrl' => $launchUrl]);
?>
