<?php
require_once '../../config.php';
header('Content-Type: application/json');
// Debug endpoint to verify AJAX path and session/auth status
try {
    $debug = [
        'reachable' => true,
        'time' => date('c'),
        'isAdmin' => isAdmin(),
        'session_id' => session_id(),
        'session_admin_id' => $_SESSION['admin_id'] ?? null,
        'session_admin_role' => $_SESSION['admin_role'] ?? null,
        'cookies' => $_COOKIE
    ];

    echo json_encode(['success' => true, 'debug' => $debug], JSON_UNESCAPED_UNICODE);
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
