<?php
require_once 'config.php';

$message = '';
$success = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        // Check if token matches session token (for demo purposes)
        if (isset($_SESSION['email_verification_token']) &&
            isset($_SESSION['email_verification_user_id']) &&
            $_SESSION['email_verification_token'] === $token) {

            // Update user email verification status
            $stmt = $db->prepare("UPDATE users SET email_verified = 1, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND email_verified = 0");
            $result = $stmt->execute([$_SESSION['email_verification_user_id']]);

            if ($result && $stmt->rowCount() > 0) {
                $message = 'تم تفعيل بريدك الإلكتروني بنجاح! يمكنك الآن الاستفادة من جميع ميزات الموقع.';
                $success = true;

                // Clear verification session
                unset($_SESSION['email_verification_token']);
                unset($_SESSION['email_verification_user_id']);
            } else {
                $message = 'البريد الإلكتروني مفعل مسبقاً أو رابط التفعيل غير صحيح.';
            }
        } else {
            $message = 'رابط التفعيل غير صحيح أو منتهي الصلاحية.';
        }
    } catch (Exception $e) {
        $message = 'حدث خطأ في تفعيل البريد الإلكتروني: ' . $e->getMessage();
    }
} else {
    $message = 'رابط التفعيل غير صحيح.';
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفعيل البريد الإلكتروني - أحمد أبو المجد</title>
    <link rel="stylesheet" href="/static/css/site-layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .verify-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 40px 30px;
            text-align: center;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
        }

        .verify-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .verify-success {
            color: var(--site-success);
        }

        .verify-error {
            color: var(--site-error);
        }

        .verify-title {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: var(--site-gray-900);
        }

        .verify-message {
            color: var(--site-gray-600);
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .verify-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--site-primary), var(--site-secondary));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--site-gray-100);
            color: var(--site-gray-700);
            border: 1px solid var(--site-gray-300);
        }

        .btn-secondary:hover {
            background: var(--site-gray-200);
        }
    </style>
</head>
<body>
    <div id="site-header"></div>

    <main class="verify-container">
        <div class="verify-icon <?php echo $success ? 'verify-success' : 'verify-error'; ?>">
            <i class="fas fa-<?php echo $success ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        </div>

        <h1 class="verify-title">
            <?php echo $success ? 'تم التفعيل بنجاح!' : 'فشل في التفعيل'; ?>
        </h1>

        <p class="verify-message">
            <?php echo $message; ?>
        </p>

        <div class="verify-actions">
            <?php if ($success): ?>
                <a href="/profile.html" class="btn btn-primary">
                    <i class="fas fa-user"></i> عرض الملف الشخصي
                </a>
                <a href="/index.html" class="btn btn-secondary">
                    <i class="fas fa-home"></i> الصفحة الرئيسية
                </a>
            <?php else: ?>
                <a href="/profile.html" class="btn btn-primary">
                    <i class="fas fa-user"></i> الملف الشخصي
                </a>
                <a href="/contact.html" class="btn btn-secondary">
                    <i class="fas fa-envelope"></i> تواصل معنا
                </a>
            <?php endif; ?>
        </div>
    </main>

    <div id="site-footer"></div>

    <script src="/static/js/site-layout.js"></script>
</body>
</html>