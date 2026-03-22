<?php
// Disable output buffering
if (ob_get_level()) ob_end_clean();

require_once '../../config.php';

// Check if user is admin
if (!isAdmin()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'غير مصرح بالدخول'], JSON_UNESCAPED_UNICODE);
    exit;
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$response = ['success' => false, 'message' => ''];

try {
    switch ($action) {
        case 'list':
            $stmt = $db->prepare("SELECT * FROM services ORDER BY display_order ASC, created_at DESC");
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = ['success' => true, 'data' => $services];
            break;

        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                $response['message'] = 'معرف الخدمة مطلوب';
                break;
            }

            $stmt = $db->prepare("SELECT * FROM services WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($service) {
                $response = ['success' => true, 'data' => $service];
            } else {
                $response['message'] = 'الخدمة غير موجودة';
            }
            break;

        case 'create':
            $title = sanitize($_POST['title'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $icon = sanitize($_POST['icon'] ?? 'fa-star');
            $priceMin = !empty($_POST['priceMin']) ? floatval($_POST['priceMin']) : null;
            $priceMax = !empty($_POST['priceMax']) ? floatval($_POST['priceMax']) : null;
            $duration = sanitize($_POST['duration'] ?? '');
            $displayOrder = intval($_POST['displayOrder'] ?? 0);
            $status = in_array($_POST['status'] ?? 'active', ['active', 'inactive']) ? $_POST['status'] : 'active';

            // Validation
            if (empty($title) || strlen($title) < 3) {
                $response['message'] = 'عنوان الخدمة مطلوب ويجب أن يكون 3 أحرف على الأقل';
                break;
            }

            if (empty($description) || strlen($description) < 10) {
                $response['message'] = 'وصف الخدمة مطلوب ويجب أن يكون 10 أحرف على الأقل';
                break;
            }

            if ($priceMin !== null && $priceMax !== null && $priceMin >= $priceMax) {
                $response['message'] = 'السعر الأدنى يجب أن يكون أقل من السعر الأعلى';
                break;
            }

            $stmt = $db->prepare("INSERT INTO services (title, description, icon, price_min, price_max, duration, display_order, status, created_at, updated_at) VALUES (:title, :description, :icon, :price_min, :price_max, :duration, :display_order, :status, NOW(), NOW())");
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':icon' => $icon,
                ':price_min' => $priceMin,
                ':price_max' => $priceMax,
                ':duration' => $duration,
                ':display_order' => $displayOrder,
                ':status' => $status
            ]);

            $newId = $db->lastInsertId();
            logActivity($db, $_SESSION['admin_id'], 'service_create', "إضافة خدمة: {$title}", 'services', $newId);
            $response = ['success' => true, 'message' => 'تم إضافة الخدمة بنجاح', 'id' => $newId];
            break;

        case 'update':
            $id = intval($_POST['serviceId'] ?? 0);
            if (!$id) {
                $response['message'] = 'معرف الخدمة مطلوب';
                break;
            }

            $title = sanitize($_POST['title'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $icon = sanitize($_POST['icon'] ?? 'fa-star');
            $priceMin = !empty($_POST['priceMin']) ? floatval($_POST['priceMin']) : null;
            $priceMax = !empty($_POST['priceMax']) ? floatval($_POST['priceMax']) : null;
            $duration = sanitize($_POST['duration'] ?? '');
            $displayOrder = intval($_POST['displayOrder'] ?? 0);
            $status = in_array($_POST['status'] ?? 'active', ['active', 'inactive']) ? $_POST['status'] : 'active';

            // Validation
            if (empty($title) || strlen($title) < 3) {
                $response['message'] = 'عنوان الخدمة مطلوب ويجب أن يكون 3 أحرف على الأقل';
                break;
            }

            if (empty($description) || strlen($description) < 10) {
                $response['message'] = 'وصف الخدمة مطلوب ويجب أن يكون 10 أحرف على الأقل';
                break;
            }

            if ($priceMin !== null && $priceMax !== null && $priceMin >= $priceMax) {
                $response['message'] = 'السعر الأدنى يجب أن يكون أقل من السعر الأعلى';
                break;
            }

            // Check if service exists
            $checkStmt = $db->prepare("SELECT id FROM services WHERE id = :id");
            $checkStmt->execute([':id' => $id]);
            if (!$checkStmt->fetch()) {
                $response['message'] = 'الخدمة غير موجودة';
                break;
            }

            $stmt = $db->prepare("UPDATE services SET title = :title, description = :description, icon = :icon, price_min = :price_min, price_max = :price_max, duration = :duration, display_order = :display_order, status = :status, updated_at = NOW() WHERE id = :id");
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':icon' => $icon,
                ':price_min' => $priceMin,
                ':price_max' => $priceMax,
                ':duration' => $duration,
                ':display_order' => $displayOrder,
                ':status' => $status,
                ':id' => $id
            ]);

            logActivity($db, $_SESSION['admin_id'], 'service_update', "تحديث خدمة: {$title}", 'services', $id);
            $response = ['success' => true, 'message' => 'تم تحديث الخدمة بنجاح'];
            break;

        case 'delete':
            $id = intval($_POST['id'] ?? 0);
            if (!$id) {
                $response['message'] = 'معرف الخدمة مطلوب';
                break;
            }

            $stmt = $db->prepare("SELECT title FROM services WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($service) {
                $delete = $db->prepare("DELETE FROM services WHERE id = :id");
                $delete->execute([':id' => $id]);
                logActivity($db, $_SESSION['admin_id'], 'service_delete', "حذف خدمة: {$service['title']}", 'services', $id);
                $response = ['success' => true, 'message' => 'تم حذف الخدمة بنجاح'];
            } else {
                $response['message'] = 'الخدمة غير موجودة';
            }
            break;

        default:
            $response['message'] = 'إجراء غير معروف';
    }
} catch(PDOException $e) {
    error_log('Services AJAX Error: ' . $e->getMessage());
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
} catch(Exception $e) {
    error_log('Services AJAX Exception: ' . $e->getMessage());
    $response['message'] = 'خطأ غير متوقع: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);