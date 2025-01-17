<?php

function errorResponse($msg)
{
    global $conn;

    header('Content-Type: application/json');
    echo json_encode(['error' => $msg]);
    if (isset($conn)) {
        $conn->close();
    }
    exit;
}

function successReponse($data)
{
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $data]);
    if (isset($conn)) {
        $conn->close();
    }
    exit;
}
?>