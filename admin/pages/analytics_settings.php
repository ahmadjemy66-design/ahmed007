<?php
require_once '../../config.php';
requireAdmin();

$ga_id = getSetting($db, 'analytics_ga_id', '');
$gsc_code = getSetting($db, 'analytics_gsc_code', '');
$yandex_id = getSetting($db, 'analytics_yandex_id', '');
$custom_enabled = getSetting($db, 'analytics_custom_enabled', '0');
$custom_endpoint = getSetting($db, 'analytics_custom_endpoint', '');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إعدادات التحليلات - لوحة التحكم</title>
    <link rel="stylesheet" href="/static/css/site-layout.css">
    <style>
        .panel{max-width:900px;margin:24px auto;background:#fff;padding:18px;border-radius:12px}
        label{display:block;margin-bottom:6px;font-weight:700}
        input[type=text], textarea{width:100%;padding:10px;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:12px}
        .row{display:flex;gap:12px}
        .row > *{flex:1}
        .save-btn{background:linear-gradient(135deg,#08137b,#4f09a7);color:#fff;padding:10px 14px;border:none;border-radius:8px;cursor:pointer}
    </style>
</head>
<body>
    <div class="container">
        <h2>إعدادات التحليلات</h2>
        <div class="panel">
            <p>ادخل مفاتيح وأكواد خدمات التحليلات التي تريد تمكينها في الموقع. هذه الإعدادات تُدار من لوحة التحكم.</p>
            <form id="analyticsForm">
                <label>Google Analytics Tracking ID (أو GA4 measurement id)</label>
                <input type="text" name="ga_id" value="<?php echo htmlspecialchars($ga_id); ?>">

                <label>Google Search Console Verification Code</label>
                <input type="text" name="gsc_code" value="<?php echo htmlspecialchars($gsc_code); ?>">

                <label>Yandex Metrika ID</label>
                <input type="text" name="yandex_id" value="<?php echo htmlspecialchars($yandex_id); ?>">

                <div class="row">
                    <div>
                        <label>تمكين التحليلات المخصصة</label>
                        <select name="custom_enabled">
                            <option value="0" <?php echo $custom_enabled === '0' ? 'selected' : ''; ?>>لا</option>
                            <option value="1" <?php echo $custom_enabled === '1' ? 'selected' : ''; ?>>نعم</option>
                        </select>
                    </div>
                    <div>
                        <label>نقطة نهاية التحليلات المخصصة (URL)</label>
                        <input type="text" name="custom_endpoint" value="<?php echo htmlspecialchars($custom_endpoint); ?>">
                    </div>
                </div>

                <div style="margin-top:12px">
                    <button class="save-btn" type="button" id="saveBtn">حفظ الإعدادات</button>
                </div>
            </form>
            <div id="status" style="margin-top:12px;color:green"></div>
        </div>
    </div>
    <script>
        document.getElementById('saveBtn').addEventListener('click', async function(){
            const form = document.getElementById('analyticsForm');
            const data = new FormData(form);
            data.append('action','save');
            const res = await fetch('/admin/ajax/analytics_settings.php', { method: 'POST', body: data });
            const json = await res.json();
            const status = document.getElementById('status');
            if (json.success) { status.textContent = 'تم الحفظ.'; } else { status.style.color='red'; status.textContent = json.message || 'خطأ أثناء الحفظ'; }
        });
    </script>
    <script src="/static/js/animations.js"></script>
</body>
</html>
