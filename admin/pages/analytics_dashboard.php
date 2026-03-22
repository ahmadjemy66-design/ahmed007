<?php
// Get analytics data
$summaryStmt = $db->prepare("
    SELECT 
        COUNT(*) as total_records
    FROM analytics_page_views
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
");
$summaryStmt->execute();
$summary = $summaryStmt->fetch();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحليلات والإحصائيات</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #08137b;
            --secondary-purple: #4f09a7;
            --white: #ffffff;
            --neutral-light: #f5f5f0;
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --border-radius: 16px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', sans-serif; background: var(--neutral-light); direction: rtl; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }

        .page-header {
            background: var(--white);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: var(--shadow-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 { color: var(--primary-blue); font-size: 2rem; }

        .filter-section {
            background: var(--white);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            box-shadow: var(--shadow-md);
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            color: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            text-align: center;
        }

        .stat-number { font-size: 2.5rem; font-weight: 800; margin-bottom: 10px; }
        .stat-label { font-size: 0.9rem; opacity: 0.9; }

        .chart-section {
            background: var(--white);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: var(--shadow-md);
        }

        .chart-title { color: var(--primary-blue); font-size: 1.3rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #f5f5f5;
            padding: 12px;
            text-align: right;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:hover { background: #f9f9f9; }

        .empty-state { text-align: center; padding: 40px 20px; color: #999; }
        .empty-state i { font-size: 2.5rem; margin-bottom: 10px; color: #ccc; }

        .btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(8, 19, 123, 0.3); }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-chart-line"></i> التحليلات والإحصائيات</h1>
            <a href="index.php" style="color: var(--primary-blue); text-decoration: none;">← العودة</a>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <label><strong>الفترة الزمنية:</strong></label>
            <select id="daysFilter" onchange="loadAnalytics()" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="7">آخر 7 أيام</option>
                <option value="30" selected>آخر 30 يوم</option>
                <option value="60">آخر 60 يوم</option>
                <option value="90">آخر 90 يوم</option>
            </select>
            <button class="btn" id="exportBtn" onclick="exportAnalyticsCSV()" style="margin-left: auto;">⤓ تصدير CSV</button>
        </div>

        <!-- Summary Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" id="totalSessions">-</div>
                <div class="stat-label">إجمالي الجلسات</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalViews">-</div>
                <div class="stat-label">إجمالي صفحات</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="avgDuration">-</div>
                <div class="stat-label">متوسط المدة (ثانية)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="newUsers">-</div>
                <div class="stat-label">مستخدمين جدد</div>
            </div>
        </div>

        <!-- Daily Traffic Chart -->
        <div class="chart-section">
            <div class="chart-title"><i class="fas fa-chart-area"></i> حركة المرور اليومية</div>
            <canvas id="dailyTrafficChart" style="height: 320px;"></canvas>
        </div>

        <!-- Device Breakdown Pie -->
        <div class="chart-section">
            <div class="chart-title"><i class="fas fa-pie-chart"></i> توزيع الأجهزة</div>
            <canvas id="devicePieChart" style="height: 320px;"></canvas>
        </div>

        <!-- Popular Pages -->
        <div class="chart-section">
            <div class="chart-title"><i class="fas fa-fire"></i> أكثر الصفحات زيارة</div>
            <table id="pagesTable">
                <thead>
                    <tr>
                        <th>اسم الصفحة</th>
                        <th>عدد الزيارات</th>
                        <th>متوسط المدة (ثانية)</th>
                    </tr>
                </thead>
                <tbody id="pagesBody">
                    <tr><td colspan="3" class="empty-state">جاري التحميل...</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Device Statistics -->
        <div class="chart-section">
            <div class="chart-title"><i class="fas fa-mobile"></i> إحصائيات الأجهزة</div>
            <table id="devicesTable">
                <thead>
                    <tr>
                        <th>نوع الجهاز</th>
                        <th>عدد الزيارات</th>
                        <th>عدد المستخدمين</th>
                        <th>متوسط المدة</th>
                    </tr>
                </thead>
                <tbody id="devicesBody">
                    <tr><td colspan="4" class="empty-state">جاري التحميل...</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Top Referrers -->
        <div class="chart-section">
            <div class="chart-title"><i class="fas fa-link"></i> أهم مصادر الزيارات</div>
            <table id="referrersTable">
                <thead>
                    <tr>
                        <th>المصدر</th>
                        <th>عدد الزيارات</th>
                        <th>المستخدمين الفريدين</th>
                    </tr>
                </thead>
                <tbody id="referrersBody">
                    <tr><td colspan="3" class="empty-state">جاري التحميل...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        let dailyTrafficChart = null;
        let devicePieChart = null;

        function loadAnalytics() {
            const days = document.getElementById('daysFilter').value;

            // Load summary
            fetch(`/admin/ajax/analytics.php?action=summary&days=${days}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('totalSessions').textContent = data.data.total_sessions || 0;
                        document.getElementById('totalViews').textContent = data.data.total_page_views || 0;
                        document.getElementById('avgDuration').textContent = data.data.avg_duration || 0;
                        document.getElementById('newUsers').textContent = data.data.new_users || 0;
                    }
                });

            loadDailyStats(days);
            loadDeviceChart(days);

            // Load pages
            fetch(`/admin/ajax/analytics.php?action=page_stats`)
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('pagesBody');
                    tbody.innerHTML = '';
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(page => {
                            tbody.innerHTML += `
                                <tr>
                                    <td>${page.page_title || page.page_type}</td>
                                    <td>${page.total_views}</td>
                                    <td>${Math.round(page.avg_duration || 0)}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="3" class="empty-state">لا توجد بيانات</td></tr>';
                    }
                });

            // Load devices
            fetch(`/admin/ajax/analytics.php?action=device_stats`)
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('devicesBody');
                    tbody.innerHTML = '';
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(device => {
                            tbody.innerHTML += `
                                <tr>
                                    <td>${device.device_type}</td>
                                    <td>${device.count}</td>
                                    <td>${device.unique_users}</td>
                                    <td>${Math.round(device.avg_duration || 0)}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" class="empty-state">لا توجد بيانات</td></tr>';
                    }
                });

            // Load referrers
            fetch(`/admin/ajax/analytics.php?action=top_referrers`)
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('referrersBody');
                    tbody.innerHTML = '';
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(ref => {
                            const referrer = ref.referrer ? ref.referrer.substring(0, 50) : 'مباشر';
                            tbody.innerHTML += `
                                <tr>
                                    <td title="${ref.referrer}">${referrer}...</td>
                                    <td>${ref.count}</td>
                                    <td>${ref.unique_sessions}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="3" class="empty-state">لا توجد بيانات</td></tr>';
                    }
                });
        }

        function loadDailyStats(days) {
            fetch(`/admin/ajax/analytics.php?action=daily_stats&days=${days}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const labels = data.data.map(row => row.date).reverse();
                    const visits = data.data.map(row => row.visits).reverse();
                    const sessions = data.data.map(row => row.unique_sessions).reverse();

                    const ctx = document.getElementById('dailyTrafficChart').getContext('2d');
                    if (dailyTrafficChart) {
                        dailyTrafficChart.data.labels = labels;
                        dailyTrafficChart.data.datasets[0].data = visits;
                        dailyTrafficChart.data.datasets[1].data = sessions;
                        dailyTrafficChart.update();
                    } else {
                        dailyTrafficChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels,
                                datasets: [
                                    { label: 'زيارات الصفحة', data: visits, borderColor: '#08137b', backgroundColor: 'rgba(8, 19, 123, 0.2)', tension: 0.3 },
                                    { label: 'الجلسات المميزة', data: sessions, borderColor: '#4f09a7', backgroundColor: 'rgba(79, 9, 167, 0.2)', tension: 0.3 }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'top' },
                                    tooltip: { mode: 'index', intersect: false }
                                },
                                scales: {
                                    x: { display: true, title: { display: true, text: 'التاريخ' } },
                                    y: { display: true, title: { display: true, text: 'العدد' }, beginAtZero: true }
                                }
                            }
                        });
                    }
                });
        }

        function loadDeviceChart(days) {
            fetch(`/admin/ajax/analytics.php?action=device_stats&days=${days}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;

                    const labels = data.data.map(item => item.device_type);
                    const counts = data.data.map(item => item.count);

                    const ctx = document.getElementById('devicePieChart').getContext('2d');
                    if (devicePieChart) {
                        devicePieChart.data.labels = labels;
                        devicePieChart.data.datasets[0].data = counts;
                        devicePieChart.update();
                    } else {
                        devicePieChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels,
                                datasets: [{
                                    data: counts,
                                    backgroundColor: ['#08137b', '#4f09a7', '#c5a47e'],
                                    hoverOffset: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { position: 'bottom' },
                                    tooltip: {
                                        callbacks: {
                                            label: ctx => `${ctx.label}: ${ctx.formattedValue} زيارة`
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
        }

        function exportAnalyticsCSV() {
            const days = document.getElementById('daysFilter').value;
            window.location.href = `/admin/ajax/analytics.php?action=export_csv&days=${days}`;
        }

        // Load analytics on page load
        loadAnalytics();
    </script>
</body>
</html>
