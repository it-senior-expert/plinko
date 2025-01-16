<?php
// Database configuration
$host = 'mysql-306b09b7-it-4911.i.aivencloud.com';
$port = 10784;
$db = 'defaultdb';
$user = 'avnadmin';
$pass = 'AVNS_wl9L7QlTJ0n5yuHJ_W9'; // Replace with the actual password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS userInfors (
    userId VARCHAR(255) NOT NULL PRIMARY KEY,
    gameId VARCHAR(255) NOT NULL,
    lang VARCHAR(10) NOT NULL,
    money DECIMAL(10, 2) NOT NULL,
    homeUrl VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL
)";
// if ($conn->query($sql) === TRUE) {
//     echo "Table userInfors created successfully";
// } else {
//     echo "Error creating table: " . $conn->error;
// }

?>
