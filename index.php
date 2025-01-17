<?php
require_once 'php/env_loader.php';
require 'php/db_connection.php';
require 'php/json_response.php';
require 'php/jwt.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = $_GET['token'];
    if (isset($token)) {
        if (!verifyToken($token)) {
            errorResponse("invalid token");
        }
        $userInfo = getUserByToken($token);
        if (!$userInfo) {
            errorResponse("invalid token");
        }

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
                    <script src='js/game_logic.js'></script>
                    <script>
    
                        // Initialize game
                        game.Init('#game');
                        initializeGame('$token');
    
                    </script>
    
                </body>
                </html>
                ";
    } else {
        errorResponse("missing token!");
    }
}

// Close connection
$conn->close();
?>