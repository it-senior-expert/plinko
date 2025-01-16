<?php
header('Content-Type: application/json');

require 'php/db_connection.php'; // Include the database connection

// Function to check user with token
function checkUserWithToken($token) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM userInfors WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return ['success' => true, 'user' => $user];
    } else {
        return ['success' => false, 'message' => 'User not found'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];
    $response = checkUserWithToken($token);
    if ($response['success']) {
        header('Content-Type: text/html; charset=UTF-8');
        echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Game Framework</title>
                <link rel='shortcut icon' href='#'>

                <link rel='stylesheet' href='css/game.css'>
                <style>
                    html,body {
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

                <div class='game-container'>
                    <div id='game'></div>
                </div>

                <!-- Components -->
                <script src='js/vendor/matter.min.js'></script>
                <script src='js/vendor/matter-attractors.js'></script>
                <script src='js/components/gHelper.js'></script>
                <script src='js/components/gIcon.js'></script>
                <script src='js/components/gUI.js'></script>
                
                <!-- Game -->
                <script src='js/components/game.js'></script>
                <script>

                    // Initialize game
                    game.Init('#game');

                </script>

            </body>
            </html>
            ";
        exit();
    }
    else{
        echo "Not Found";
    }
}

// Close connection
$conn->close();
?>

