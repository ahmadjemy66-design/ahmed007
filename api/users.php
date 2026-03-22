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
        $stmt = $db->prepare("SELECT id, username, full_name, email, phone, bio, avatar_url, country, preferred_language, email_verified, newsletter_subscribed, status, last_login, created_at, updated_at FROM users WHERE id = ? LIMIT 1");
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
        $bio = sanitize($_POST['bio'] ?? '');
        $preferred_language = sanitize($_POST['preferred_language'] ?? 'ar');
        $newsletter_subscribed = isset($_POST['newsletter_subscribed']) && $_POST['newsletter_subscribed'] === '1' ? 1 : 0;

        if (!$full_name) throw new Exception('Full name required');

        $stmt = $db->prepare("UPDATE users SET
            full_name = :full_name,
            phone = :phone,
            country = :country,
            bio = :bio,
            preferred_language = :preferred_language,
            newsletter_subscribed = :newsletter_subscribed,
            updated_at = CURRENT_TIMESTAMP
            WHERE id = :id");

        $stmt->execute([
            ':full_name' => $full_name,
            ':phone' => $phone,
            ':country' => $country,
            ':bio' => $bio,
            ':preferred_language' => $preferred_language,
            ':newsletter_subscribed' => $newsletter_subscribed,
            ':id' => $_SESSION['user_id']
        ]);

        echo json_encode(['success' => true, 'message' => 'Profile updated successfully'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'upload_avatar') {
        if (!isset($_SESSION['user_id'])) throw new Exception('User not logged in', 401);
        if (!isUserActive()) throw new Exception('User not allowed', 403);

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('No avatar file uploaded');
        }

        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('File size too large. Maximum 5MB allowed');
        }

        // Create uploads directory if it doesn't exist
        $uploadDir = '../static/uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Delete old avatar if exists
            $stmt = $db->prepare("SELECT avatar_url FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $oldAvatar = $stmt->fetchColumn();

            if ($oldAvatar && file_exists('../' . $oldAvatar)) {
                unlink('../' . $oldAvatar);
            }

            // Update database with new avatar URL
            $avatarUrl = '/static/uploads/avatars/' . $filename;
            $stmt = $db->prepare("UPDATE users SET avatar_url = :avatar_url WHERE id = :id");
            $stmt->execute([':avatar_url' => $avatarUrl, ':id' => $_SESSION['user_id']]);

            echo json_encode(['success' => true, 'message' => 'Avatar uploaded successfully', 'avatar_url' => $avatarUrl], JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception('Failed to save avatar file');
        }
        exit;
    }

    if ($action === 'change_password') {
        if (!isset($_SESSION['user_id'])) throw new Exception('User not logged in', 401);
        if (!isUserActive()) throw new Exception('User not allowed', 403);

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';

        if (!$current_password || !$new_password) {
            throw new Exception('Current password and new password are required');
        }

        if (strlen($new_password) < 8) {
            throw new Exception('New password must be at least 8 characters long');
        }

        // Verify current password
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $hashedPassword = $stmt->fetchColumn();

        if (!password_verify($current_password, $hashedPassword)) {
            throw new Exception('Current password is incorrect');
        }

        // Hash new password
        $newHashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password
        $stmt = $db->prepare("UPDATE users SET password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->execute([':password' => $newHashedPassword, ':id' => $_SESSION['user_id']]);

        echo json_encode(['success' => true, 'message' => 'Password changed successfully'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'resend_verification') {
        if (!isset($_SESSION['user_id'])) throw new Exception('User not logged in', 401);
        if (!isUserActive()) throw new Exception('User not allowed', 403);

        $stmt = $db->prepare("SELECT email, email_verified FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user) throw new Exception('User not found', 404);

        if ($user['email_verified']) {
            throw new Exception('Email is already verified');
        }

        // Generate verification token (store in session for demo - in production use database)
        $verification_token = bin2hex(random_bytes(32));
        $_SESSION['email_verification_token'] = $verification_token;
        $_SESSION['email_verification_user_id'] = $_SESSION['user_id'];

        // In production, send actual email here
        // For demo purposes, we'll just return success
        $verification_link = "https://" . $_SERVER['HTTP_HOST'] . "/email-verify.php?token=" . $verification_token;

        echo json_encode([
            'success' => true,
            'message' => 'Verification email sent successfully',
            'verification_link' => $verification_link // For demo purposes
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'export_data') {
        if (!isset($_SESSION['user_id'])) throw new Exception('User not logged in', 401);
        if (!isUserActive()) throw new Exception('User not allowed', 403);

        // Get user data
        $stmt = $db->prepare("SELECT id, username, full_name, email, phone, bio, country, preferred_language, email_verified, newsletter_subscribed, status, last_login, created_at, updated_at FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user) throw new Exception('User not found', 404);

        // Get user's orders
        $stmt = $db->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $orders = $stmt->fetchAll();

        // Get user's reviews
        $stmt = $db->prepare("SELECT id, product_id, rating, comment, created_at FROM reviews WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $reviews = $stmt->fetchAll();

        $exportData = [
            'user_profile' => $user,
            'orders' => $orders,
            'reviews' => $reviews,
            'export_date' => date('Y-m-d H:i:s'),
            'export_version' => '1.0'
        ];

        echo json_encode(['success' => true, 'data' => $exportData], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid action'], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    $code = $e->getCode() ?: 400;
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
