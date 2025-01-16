<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . '/../vendor/autoload.php';
require 'db_connection.php'; // Include the database connection

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['userId'])) {
        $_SESSION['userId'] = $_POST['userId'];
    }
    if (isset($_POST['gameId'])) {
        $_SESSION['gameId'] = $_POST['gameId'];
    }
    if (isset($_POST['lang'])) {
        $_SESSION['lang'] = $_POST['lang'];
    }
    if (isset($_POST['money'])) {
        $_SESSION['money'] = $_POST['money'];
    }
    if (isset($_POST['home_url'])) {
        $_SESSION['homeUrl'] = $_POST['home_url'];
    }
}

$userId = $_SESSION['userId'] ?? null;
$gameId = $_SESSION['gameId'] ?? null;
$lang = $_SESSION['lang'] ?? 'en';
$money = $_SESSION['money'] ?? 0.0;
$home_url = $_SESSION['homeUrl'] ?? null;

$apiKey = $_ENV['X_API_KEY'];
$headers = getallheaders();
if (!isset($headers['X-API-Key']) || $headers['X-API-Key'] !== $apiKey) {
    echo json_encode(['success' => false, 'message' => 'Invalid API Key']);
    exit;
}

$tokenFromUrl = $_GET['token'] ?? null;

// Function to generate a token
function generateToken($userId, $gameId, $lang, $money, $home_url) {
    $timestamp = time();
    $expiration = $timestamp + 3600; // 1 hour expiration
    $randomString = bin2hex(random_bytes(5));
    return hash('sha256', $userId . $gameId . $lang . $money . $home_url . $timestamp . $expiration . $randomString);
}

// Generate token
$token = generateToken($userId, $gameId, $lang, $money, $home_url);


// Prepare and bind
$stmt = $conn->prepare("INSERT INTO userInfors (userId, gameId, lang, money, homeUrl, token) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdss", $userId, $gameId, $lang, $money, $home_url, $token);

// Execute the statement
if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Send token to client
echo json_encode(['success' => true, 'token' => $token]);

// Close connections
$stmt->close();
$conn->close();
?>
