<?php
require __DIR__ . '/../vendor/autoload.php';
require 'db_connection.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $apiKey = $_ENV['X_API_KEY'];
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($data['action']) {
        case 'update_transaction':
            echo json_encode(updateTransaction($data));
            break;
        case 'check_transaction':
            echo json_encode(checkTransaction($data));
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

function checkTransaction($transactionData) {
    $url = $_ENV['URL'];
    unset($transactionData['action']);

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

function updateTransaction($transactionData) {
    $url = $_ENV['URL'];

    unset($transactionData['action']);

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
