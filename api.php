<?php
// api.php
header('Content-Type: application/json');

// Database connection settings (replace with your actual database details)
$host = 'your_host';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Handle API actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($data['action']) {
        case 'get_balance':
            echo json_encode(getUserBalance($data['userId'], $pdo));
            break;
        case 'update_transaction':
            echo json_encode(updateTransaction($data, $pdo));
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

// Function to get user balance
function getUserBalance($userId, $pdo, $homeUrl = null) {
    try {
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $balance = $stmt->fetchColumn();
        if ($balance !== false) {
            return ['success' => true, 'balance' => $balance];
        } else {
            if ($homeUrl) {
                header("Location: $homeUrl");
                exit;
            } else {
                return ['success' => false, 'message' => 'User not found'];
            }
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error fetching balance: ' . $e->getMessage()];
    }
}

// Function to update transaction
function updateTransaction($transactionData, $pdo) {
    try {
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, bet_amount, win_amount, game_uid, game_round, serial_number, currency_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $transactionData['bet_amount'],
            $transactionData['win_amount'],
            $transactionData['member_account'],
            $transactionData['game_uid'],
            $transactionData['game_round'],
            $transactionData['serial_number'],
            $transactionData['currency_code']
        ]);
        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Transaction recorded'];
        } else {
            return ['success' => false, 'message' => 'Failed to record transaction'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error updating transaction: ' . $e->getMessage()];
    }
}
?>
