<?php
require_once 'env_loader.php';
// Database configuration
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$db = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

// Database Table
$tableName = $_ENV['DB_TBL_NAME'];

// Create a connection
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function isExistUser($userId)
{
    global $conn, $tableName;
    try {
        $stmt = $conn->prepare("SELECT userId FROM {$tableName} WHERE userId = ?");
        $stmt->bind_param("s", $userId);
        if (!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        throw $th;
    }
}

function updateOne($userId, $gameUid)
{
    global $conn, $tableName;
    try {
        $stmt = $conn->prepare("UPDATE {$tableName} SET gameUid = ? WHERE userId = ?");
        $stmt->bind_param("ss", $gameUid, $userId);
        if (!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }
        $stmt->execute();
    } catch (\Throwable $th) {
        throw $th;
    }
}

function updateTable($userId, $gameId, $lang, $money, $homeUrl, $token, $gameRound)
{
    global $conn, $tableName;
    try {
        $stmt = $conn->prepare("UPDATE {$tableName} SET gameId = ?, lang = ?, money = ?, homeUrl = ?, token = ?, gameRound = ? WHERE userId = ?");
        $stmt->bind_param("ssdssss", $gameId, $lang, $money, $homeUrl, $token, $gameRound, $userId);
        if (!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }
        $stmt->close();
    } catch (\Throwable $th) {
        throw $th;
    }
}

function upsertTable($userId, $gameId, $lang, $money, $homeUrl, $token, $gameRound)
{
    global $conn, $tableName;
    try {
        $isExist = isExistUser($userId);
        if ($isExist) {
            updateTable($userId, $gameId, $lang, $money, $homeUrl, $token, $gameRound);
        } else {
            $stmt = $conn->prepare("INSERT INTO {$tableName} (userId, gameId, lang, money, homeUrl, token, gameRound) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdsss", $userId, $gameId, $lang, $money, $homeUrl, $token, $gameRound);
            if (!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }
            $stmt->close();
        }
    } catch (\Throwable $th) {
        throw $th;
    }
}
function upsertOne($userId, $gameUid)
{
    global $conn, $tableName;
    try {
        $isExist = isExistUser($userId);
        if ($isExist) {
            updateOne($userId, $gameUid);
        } else {
            errorResponse("Missing User");
        }
    } catch (\Throwable $th) {
        throw $th;
    }
}

function getUserByToken($token): array|bool|null
{
    global $conn, $tableName;
    try {
        $row = null;
        $stmt = $conn->prepare("SELECT * FROM {$tableName} WHERE token = ?");
        $stmt->bind_param("s", $token);
        if (!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }
        $stmt->close();
        return $row;
    } catch (\Throwable $th) {
        throw $th;
    }
}
?>