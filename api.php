<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json');

// Handle API actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $apiKey = $_ENV['X_API_KEY'];
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($data['action']) {
        case 'get_balance':
            echo json_encode(getUserBalance($data['userId']));
            break;
        case 'update_transaction':
            echo json_encode(updateTransaction($data));
            break;
        case 'store_session':
            echo json_encode(storeSession($data));
            break;
        case 'restore_session':
            echo json_encode(restoreSession($data['sessionId']));
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

// Function to get user balance
function getUserBalance($userId, $homeUrl = null) {
    $url = $_ENV['URL'];

    $data = [
        'userId' => $userId,
        'gameId' => 'PLINKO',
        'lang' => 'en',
        'money' => 500.00,
        'home_url' => $homeUrl
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                         "Authorization: Bearer {$_ENV['X_API_KEY']}\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        if ($homeUrl) {
            header("Location: $homeUrl");
            exit;
        } else {
            return ['success' => false, 'message' => 'Error contacting API'];
        }
    } else {
        $response = json_decode($result, true);
        return [
            'success' => true,
            'message' => 'CallBack Received',
            'handle' => true,
            'money' => $response['money'] ?? 0.0
        ];
    }
}

// Function to update transaction
function updateTransaction($transactionData) {
    $url = $_ENV['URL'];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                         "Authorization: Bearer {$_ENV['X_API_KEY']}\r\n",
            'method'  => 'POST',
            'content' => http_build_query($transactionData),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        return ['success' => false, 'message' => 'Error contacting API'];
    } else {
        $response = json_decode($result, true);
        return [
            'success' => true,
            'message' => 'CallBack Received',
            'handle' => true,
            'money' => $response['money'] ?? 0.0
        ];
    }
}

// Function to store session data
function storeSession($sessionData) {
    $url = $_ENV['URL'];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                         "Authorization: Bearer {$_ENV['X_API_KEY']}\r\n",
            'method'  => 'POST',
            'content' => http_build_query($sessionData),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        return ['success' => false, 'message' => 'Error contacting API'];
    } else {
        $response = json_decode($result, true);
        return [
            'success' => true,
            'message' => 'CallBack Received',
            'handle' => true,
            'money' => $response['money'] ?? 0.0
        ];
    }
}

// Function to restore session data
function restoreSession($sessionId) {
    $url = $_ENV['URL'];

    $data = ['sessionId' => $sessionId];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                         "Authorization: Bearer {$_ENV['X_API_KEY']}\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        return ['success' => false, 'message' => 'Error contacting API'];
    } else {
        $response = json_decode($result, true);
        return [
            'success' => true,
            'message' => 'CallBack Received',
            'handle' => true,
            'money' => $response['money'] ?? 0.0
        ];
    }
}
