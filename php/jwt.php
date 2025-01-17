<?php
require __DIR__ . '/../vendor/autoload.php';
require_once 'env_loader.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


# Token generation and storing data to db
$secretKey = $_ENV['SECRET_KEY'];
$expireSecs = $_ENV['EXPIRE_SECS'] ?? 3600;

function generateToken($userId, $gameId, $lang, $money, $homeUrl)
{
    global $secretKey, $expireSecs;
    $issuedAt = time(); // Current time
    $expirationTime = $issuedAt + $expireSecs; // Token valid for $expireSecs seconds
    $payload = [
        'iss' => 'Plinko',  // Issuer of the token
        'aud' => 'NinoBets',
        'iat' => $issuedAt,           // Issued at: current time
        'exp' => $expirationTime,     // Expiration time: 1 hour later
        'data' => [                   // Custom data
            'userId' => $userId,
            'gameId' => $gameId,
            'lang' => $lang,
            'money' => $money,
            'homeUrl' => $homeUrl
        ]
    ];

    // Encode the payload to generate a JWT token
    $jwt = JWT::encode($payload, $secretKey, 'HS256');
    return $jwt;
}

function verifyToken($token)
{
    global $secretKey;
    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        // Check if the token is expired
        if (isset($decoded->exp) && time() > $decoded->exp) {
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        return false;
    }
}
?>