<?php
require __DIR__ . '/../vendor/autoload.php';
require 'db_connection.php';
require 'env_loader.php';
require 'json_response.php';

$apiKey = $_ENV['X_API_KEY'];

header('Content-Type: application/json');
$userInfo = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['token'])) {
        errorResponse('missing token!');
    }
    $token = $data['token'];
    $userInfo = getUserByToken($token);
    if (!$userInfo) {
        errorResponse('missing token!');
    }
    switch ($data['action']) {
        case 'update_transaction':
            echo json_encode(updateTransaction($data));
            break;
        case 'check_transaction':
            echo json_encode(checkTransaction($data));
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function checkTransaction($transactionData)
{
    global $apiKey, $userInfo;
    $url = $_ENV['API_URL'];
    $serialNumber = hash('sha256', $transactionData['bet_amount'] . $transactionData['win_amount'] . $userInfo['userId'] . '3735554396885692691' . $userInfo['gameRound']);
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                "Authorization: Bearer {$apiKey}\r\n",
            'method' => 'POST',
            'content' => http_build_query([
                'bet_amount' => $transactionData['bet_amount'],
                'win_amount' => $transactionData['win_amount'],
                'member_account' => $userInfo['userId'],
                'game_id' => '3735554396885692691',
                'game_round' => $userInfo['gameRound'],
                'serial_number' => $serialNumber,
                'currency_code' => 'USD'
            ]),
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    if ($response === FALSE) {
        errorResponse('Error contacting API');
    } else {
        successReponse([
            'message' => 'CallBack Received',
            'handle' => true,
            'money' => $response['money'] ?? 0.0
        ]);
    }
}

function updateTransaction($transactionData)
{
    global $apiKey, $userInfo;
    $url = $_ENV['API_URL'];
    $serialNumber = hash('sha256', $transactionData['bet_amount'] . $transactionData['win_amount'] . $userInfo['userId'] . '3735554396885692691' . $userInfo['gameRound']);
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                "Authorization: Bearer {$apiKey}\r\n",
            'method' => 'POST',
            'content' => http_build_query([
                'bet_amount' => $transactionData['bet_amount'],
                'win_amount' => $transactionData['win_amount'],
                'member_account' => $userInfo['userId'],
                'game_id' => '3735554396885692691',
                'game_round' => $userInfo['gameRound'],
                'serial_number' => $serialNumber,
                'currency_code' => 'USD'
            ]),
        ],
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        errorResponse('Error contacting API');
    } else {
        successReponse([
            'message' => 'CallBack Received',
            'handle' => true,
            'money' => $response['money'] ?? 0.0
        ]);
    }
}
