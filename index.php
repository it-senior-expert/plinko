<?php
// index.php
session_start();  // Start the session to access session variables

// Check if the user is logged in and has a session
// if (!isset($_SESSION['userId'])) {
//     // Redirect to login page if not logged in
//     header('Location: login.php');
//     exit;
// }

// Retrieve user data from session variables
$userId = $_SESSION['userId'] ?? 'defaultUserId';;  // Assuming userId is stored in session when user logs in
$gameId = $_SESSION['gameId'] ?? 'defaultGameId';  // Optional: gameId might be set after login or dynamically
$lang = $_SESSION['lang'] ?? 'en';  // Default language setting
$money = $_SESSION['money'] ?? 0;  // User's current balance, should be updated on backend after operations
$home_url = $_SESSION['homeUrl'] ?? 'defaultHomeUrl';  // Optional: Home URL could be set in session or statically

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
    <script>
        // Initialize the game with these parameters
        initializeGame({
            userId: '<?php echo $userId; ?>',
            gameId: '<?php echo $gameId; ?>',
            lang: '<?php echo $lang; ?>',
            money: <?php echo $money; ?>,
            homeUrl: '<?php echo $home_url; ?>'
        });

        // Initialize game environment
        game.Init('#game');

    </script>

</body>
</html>
