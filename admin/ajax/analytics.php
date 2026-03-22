<?php
require_once '../../config.php';

if (!isAdmin()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'غير مصرح'], JSON_UNESCAPED_UNICODE);
    exit;
}

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$days = intval($_GET['days'] ?? $_POST['days'] ?? 30);
if ($days <= 0) {
    $days = 30;
}
$response = ['success' => false, 'message' => ''];

try {
    switch ($action) {
        case 'daily_stats':
            $days = intval($_GET['days'] ?? 30);
            $stmt = $db->prepare("
                SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as visits,
                    COUNT(DISTINCT session_id) as unique_sessions
                FROM analytics_page_views 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC
            ");
            $stmt->execute([':days' => $days]);
            $stats = $stmt->fetchAll();
            $response['success'] = true;
            $response['data'] = $stats;
            break;

        case 'page_stats':
            $stmt = $db->prepare("
                SELECT 
                    page_type,
                    page_title,
                    COUNT(*) as total_views,
                    AVG(view_duration) as avg_duration
                FROM analytics_page_views
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY page_type, page_title
                ORDER BY total_views DESC
                LIMIT 20
            ");
            $stmt->execute([':days' => $days]);
            $pages = $stmt->fetchAll();
            $response['success'] = true;
            $response['data'] = $pages;
            break;

        case 'device_stats':
            $stmt = $db->prepare("
                SELECT 
                    CASE 
                        WHEN user_agent LIKE '%Mobile%' THEN 'Mobile'
                        WHEN user_agent LIKE '%Tablet%' THEN 'Tablet'
                        ELSE 'Desktop'
                    END AS device_type,
                    COUNT(*) as count,
                    COUNT(DISTINCT session_id) as unique_users,
                    AVG(view_duration) as avg_duration
                FROM analytics_page_views
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY device_type
            ");
            $stmt->execute([':days' => $days]);
            $devices = $stmt->fetchAll();
            $response['success'] = true;
            $response['data'] = $devices;
            break;

        case 'summary':
            // Get summary statistics
            $stmt = $db->prepare("
                SELECT 
                    COUNT(DISTINCT session_id) as total_sessions,
                    COUNT(*) as total_page_views,
                    AVG(view_duration) as avg_session_duration
                FROM analytics_page_views
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            ");
            $stmt->execute([':days' => $days]);
            $views = $stmt->fetch();

            $stmt = $db->prepare("
                SELECT COUNT(*) as total_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            $stmt->execute();
            $users = $stmt->fetch();

            $stmt = $db->prepare("
                SELECT COUNT(*) as total_articles FROM articles WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            $stmt->execute();
            $articles = $stmt->fetch();

            $response['success'] = true;
            $response['data'] = [
                'total_sessions' => $views['total_sessions'] ?? 0,
                'total_page_views' => $views['total_page_views'] ?? 0,
                'avg_duration' => round($views['avg_session_duration'] ?? 0),
                'new_users' => $users['total_users'] ?? 0,
                'new_articles' => $articles['total_articles'] ?? 0
            ];
            break;

        case 'top_referrers':
            $stmt = $db->prepare("
                SELECT 
                    referrer,
                    COUNT(*) as count,
                    COUNT(DISTINCT session_id) as unique_sessions
                FROM analytics_page_views
                WHERE referrer IS NOT NULL AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY referrer
                ORDER BY count DESC
                LIMIT 10
            ");
            $stmt->execute([':days' => $days]);
            $referrers = $stmt->fetchAll();
            $response['success'] = true;
            $response['data'] = $referrers;
            break;

        case 'export_csv':
            $stmt = $db->prepare("
                SELECT id, session_id, page_type, page_title, referrer, user_agent, view_duration, created_at
                FROM analytics_page_views
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                ORDER BY created_at DESC
            ");
            $stmt->execute([':days' => $days]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=analytics-export-' . date('Ymd') . '.csv');
            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF");
            fputcsv($output, ['ID', 'Session', 'Page Type', 'Page Title', 'Referrer', 'User Agent', 'Duration', 'Timestamp']);

            foreach ($rows as $row) {
                fputcsv($output, [$row['id'], $row['session_id'], $row['page_type'], $row['page_title'], $row['referrer'], $row['user_agent'], $row['view_duration'], $row['created_at']]);
            }
            fclose($output);
            exit;

        default:
            $response['message'] = 'إجراء غير صحيح';
    }
} catch (Exception $e) {
    $response['message'] = 'خطأ: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
