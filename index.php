<?php
// Start the session to access session variables
session_start();
header('Content-Type: application/json');

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

// Validate API Key
$expectedApiKey = 'THISISTHEKEY';
$headers = getallheaders();
if (!isset($headers['X-API-Key']) || $headers['X-API-Key'] !== $expectedApiKey) {
    echo json_encode(['success' => false, 'message' => 'Invalid API Key']);
    exit;
}

// Log the input variables
$logData = "UserID: $userId, GameID: $gameId, Lang: $lang, Money: $money, HomeURL: $home_url\n";
file_put_contents('game_launch_log.txt', $logData, FILE_APPEND);

// Generate the game launch URL
$launchUrl = "http://localhost/game?userId=$userId&gameId=$gameId&lang=$lang&session=" . uniqid();
// Output the launch URL for client-side redirection
echo json_encode(['success' => true, 'launchUrl' => $launchUrl]);
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
<?php

// Pass these parameters to the frontend
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Framework</title>
    <link rel="shortcut icon" href="#">

    <link rel="stylesheet" href="css/game.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        
        .game-container {
            width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="game-container">
        <div id="game"></div>
    </div>

    <!-- Components -->
    <script src="js/vendor/matter.min.js"></script>
    <script src="js/vendor/matter-attractors.js"></script>
    <script src="js/components/gHelper.js"></script>
    <script src="js/components/gIcon.js"></script>
    <script src="js/components/gUI.js"></script>
    
    <!-- Game -->
    <script src="js/components/game.js"></script>
    <script src="js/game_logic.js"></script>
</body>
</html>
