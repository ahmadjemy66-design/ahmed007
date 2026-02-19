<?php
require_once '../config.php';
header('Content-Type: application/json');
$action = $_GET['action'] ?? $_POST['action'] ?? '';
try {
    if ($action === 'me') {
        if (!isset($_SESSION['user_id'])) {
            throw new Exception('User not logged in', 401);
        }
        if (!isUserActive()) {
            throw new Exception('User not allowed', 403);
        }
        $stmt = $db->prepare("SELECT id, full_name, email, phone, country, newsletter_subscribed, email_verified FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if (!$user) throw new Exception('User not found', 404);
        echo json_encode(['success' => true, 'data' => $user], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'update') {
        if (!isset($_SESSION['user_id'])) throw new Exception('User not logged in', 401);
        if (!isUserActive()) throw new Exception('User not allowed', 403);
        $full_name = sanitize($_POST['full_name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $country = sanitize($_POST['country'] ?? '');
        if (!$full_name) throw new Exception('Full name required');
        $stmt = $db->prepare("UPDATE users SET full_name = :full_name, phone = :phone, country = :country WHERE id = :id");
        $stmt->execute([':full_name'=>$full_name, ':phone'=>$phone, ':country'=>$country, ':id'=>$_SESSION['user_id']]);
        echo json_encode(['success'=>true, 'message'=>'Profile updated'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode(['success'=>false, 'message'=>'Invalid action'], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    $code = $e->getCode() ?: 400;
    http_response_code($code);
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
