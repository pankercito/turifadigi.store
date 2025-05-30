<?php

header('Content-Type: application/json');

@session_start();

if (isset($_SESSION['usuario'])) {
    unset($_SESSION['usuario']);
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Session destroyed successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No active session found.']);
}
