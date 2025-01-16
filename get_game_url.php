<?php
// Start the session to access session variables
session_start();
header('Content-Type: application/json');

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check for input variables and store them in the session if present
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

// Retrieve user data from session variables with default values
$userId = $_SESSION['userId'] ?? null;
$gameId = $_SESSION['gameId'] ?? null;
$lang = $_SESSION['lang'] ?? 'en';  // Default language setting
$money = $_SESSION['money'] ?? 0.0;
$home_url = $_SESSION['homeUrl'] ?? null;

$apiKey = $_ENV['X_API_KEY']; // Use the API key from .env
$headers = getallheaders();
if (!isset($headers['X-API-Key']) || $headers['X-API-Key'] !== $apiKey) {
    echo json_encode(['success' => false, 'message' => 'Invalid API Key']);
    exit;
}

$logData = "UserID: $userId, GameID: $gameId, Lang: $lang, Money: $money, HomeURL: $home_url\n";
file_put_contents('game_launch_log.txt', $logData, FILE_APPEND);

// Retrieve sessionId from local session or generate a new one
$sessionId = $_SESSION['sessionId'] ?? uniqid();
$_SESSION['sessionId'] = $sessionId;

?>
<script>
    // Redirect to the game site using the launch URL
    fetch(window.location.href)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.launchUrl;
                // Initialize the game with these parameters
                initializeGame({
                    userId: '<?php echo $userId; ?>',
                    gameId: '<?php echo $gameId; ?>',
                    lang: '<?php echo $lang; ?>',
                    money: <?php echo $money; ?>,
                    homeUrl: '<?php echo $home_url; ?>'
                });
                game.Init('#game');
            }
        });
</script>
