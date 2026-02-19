<?php
require_once '../../config.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

$action = $_POST['action'] ?? '';
if ($action !== 'save') {
    echo json_encode(['success' => false, 'message' => 'إجراء غير صحيح']);
    exit;
}

$ga = isset($_POST['ga_id']) ? sanitize($_POST['ga_id']) : '';
$gsc = isset($_POST['gsc_code']) ? sanitize($_POST['gsc_code']) : '';
$yandex = isset($_POST['yandex_id']) ? sanitize($_POST['yandex_id']) : '';
$custom_enabled = isset($_POST['custom_enabled']) ? (int)$_POST['custom_enabled'] : 0;
$custom_endpoint = isset($_POST['custom_endpoint']) ? sanitize($_POST['custom_endpoint']) : '';

$ok = true;
$ok = $ok && (updateSetting($db, 'analytics_ga_id', $ga) !== false);
$ok = $ok && (updateSetting($db, 'analytics_gsc_code', $gsc) !== false);
$ok = $ok && (updateSetting($db, 'analytics_yandex_id', $yandex) !== false);
$ok = $ok && (updateSetting($db, 'analytics_custom_enabled', $custom_enabled) !== false);
$ok = $ok && (updateSetting($db, 'analytics_custom_endpoint', $custom_endpoint) !== false);

if ($ok) {
    logActivity($db, $_SESSION['admin_id'] ?? null, 'update_settings', 'Updated analytics settings');
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'فشل في حفظ بعض الإعدادات']);
}
