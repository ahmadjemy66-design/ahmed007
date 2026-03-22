<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الخدمات - لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary-blue: #08137b;
            --secondary-purple: #4f09a7;
            --accent-green: #22c55e;
            --accent-red: #ef4444;
            --white: #ffffff;
            --neutral-light: #f8fafc;
            --neutral-gray: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            --border-radius-lg: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--neutral-light);
            direction: rtl;
            color: var(--text-primary);
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Section */
        .page-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            color: var(--white);
            padding: 30px;
            border-radius: var(--border-radius-lg);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-xl);
        }

        .page-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-header h1 i {
            font-size: 2.5rem;
            opacity: 0.9;
        }

        .header-actions {
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
            background: var(--white);
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--white);
        }

        .stat-icon.services { background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple)); }
        .stat-icon.active { background: linear-gradient(135deg, var(--accent-green), #16a34a); }
        .stat-icon.inactive { background: linear-gradient(135deg, var(--accent-red), #dc2626); }

        .stat-content h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-content p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Services Grid */
        .services-section {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            padding: 30px;
            box-shadow: var(--shadow-md);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .service-card {
            background: var(--white);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card:hover {
            border-color: var(--primary-blue);
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .service-card.active {
            border-color: var(--accent-green);
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.05), rgba(22, 163, 74, 0.02));
        }

        .service-card.inactive {
            border-color: var(--accent-red);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.05), rgba(220, 38, 38, 0.02));
        }

        .service-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .service-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--white);
            flex-shrink: 0;
        }

        .service-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 5px;
        }

        .service-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        .service-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .service-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detail-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .detail-value {
            color: var(--text-primary);
            font-weight: 600;
        }

        .service-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--accent-green), #16a34a);
            color: var(--white);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: var(--white);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--accent-red), #dc2626);
            color: var(--white);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--neutral-gray);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.8rem;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: var(--white);
            margin: 5% auto;
            padding: 0;
            border-radius: var(--border-radius-lg);
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            color: var(--white);
            padding: 25px 30px;
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-btn {
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .close-btn:hover {
            transform: scale(1.1);
        }

        .modal-body {
            padding: 30px;
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-label.required::after {
            content: '*';
            color: var(--accent-red);
        }

        .form-input, .form-textarea, .form-select {
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
            font-family: inherit;
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(8, 19, 123, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-select {
            cursor: pointer;
        }

        .form-error {
            color: var(--accent-red);
            font-size: 0.8rem;
            margin-top: 5px;
            display: none;
        }

        .form-group.error .form-input,
        .form-group.error .form-textarea,
        .form-group.error .form-select {
            border-color: var(--accent-red);
        }

        .form-group.error .form-error {
            display: block;
        }

        /* Icon Picker */
        .icon-picker-container {
            position: relative;
        }

        .icon-preview {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: var(--neutral-gray);
            margin-bottom: 10px;
        }

        .icon-display {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--white);
        }

        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
            gap: 10px;
            max-height: 200px;
            overflow-y: auto;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--white);
            display: none;
        }

        .icon-option {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--neutral-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            color: var(--text-primary);
        }

        .icon-option:hover {
            background: var(--primary-blue);
            color: var(--white);
            transform: scale(1.1);
        }

        .icon-option.selected {
            background: var(--primary-blue);
            color: var(--white);
        }

        /* Price Range */
        .price-range {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 15px;
            align-items: center;
        }

        .price-input {
            position: relative;
        }

        .price-input::before {
            content: 'ريال';
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 0.9rem;
            z-index: 1;
        }

        .price-input input {
            padding-left: 45px;
        }

        /* Modal Actions */
        .modal-actions {
            padding: 25px 30px;
            background: var(--neutral-gray);
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        /* Loading */
        .loading {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
        }

        .loading::after {
            content: '';
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-top: 2px solid var(--primary-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
                padding: 20px;
            }

            .header-actions {
                flex-wrap: wrap;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                margin: 10px;
                width: calc(100% - 20px);
            }

            .modal-header, .modal-body, .modal-actions {
                padding: 20px;
            }

            .service-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .service-actions {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-concierge-bell"></i> إدارة الخدمات</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="showAddModal()">
                    <i class="fas fa-plus"></i> إضافة خدمة جديدة
                </button>
                <button class="btn btn-secondary" onclick="refreshServices()">
                    <i class="fas fa-sync-alt"></i> تحديث
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon services">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="stat-content">
                    <h3 id="totalServices">0</h3>
                    <p>إجمالي الخدمات</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 id="activeServices">0</h3>
                    <p>الخدمات النشطة</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon inactive">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 id="inactiveServices">0</h3>
                    <p>الخدمات غير النشطة</p>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="services-section">
            <div class="section-header">
                <h2><i class="fas fa-list"></i> قائمة الخدمات</h2>
                <div class="section-actions">
                    <select id="statusFilter" class="form-select" onchange="filterServices()">
                        <option value="all">جميع الخدمات</option>
                        <option value="active">النشطة فقط</option>
                        <option value="inactive">غير النشطة فقط</option>
                    </select>
                </div>
            </div>

            <div id="servicesGrid" class="services-grid">
                <!-- Services will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Add/Edit Service Modal -->
    <div id="serviceModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle"><i class="fas fa-plus"></i> إضافة خدمة جديدة</h2>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>

            <div class="modal-body">
                <form id="serviceForm" novalidate>
                    <input type="hidden" id="serviceId" name="serviceId">
                    <input type="hidden" id="icon" name="icon" value="fa-star">

                    <div class="form-grid">
                        <!-- Basic Information -->
                        <div class="form-group full-width">
                            <label class="form-label required">عنوان الخدمة</label>
                            <input type="text" id="title" name="title" class="form-input" required minlength="3" maxlength="255">
                            <div class="form-error">العنوان مطلوب ويجب أن يكون بين 3-255 حرف</div>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label required">وصف الخدمة</label>
                            <textarea id="description" name="description" class="form-textarea" required minlength="10" maxlength="1000" placeholder="اكتب وصفاً مفصلاً للخدمة..."></textarea>
                            <div class="form-error">الوصف مطلوب ويجب أن يكون بين 10-1000 حرف</div>
                        </div>

                        <!-- Icon Selection -->
                        <div class="form-group full-width">
                            <label class="form-label">أيقونة الخدمة</label>
                            <div class="icon-picker-container">
                                <div class="icon-preview" onclick="toggleIconPicker()">
                                    <div class="icon-display">
                                        <i id="selectedIcon" class="fas fa-star"></i>
                                    </div>
                                    <div>
                                        <strong>الأيقونة المختارة:</strong> <span id="selectedIconName">fa-star</span>
                                    </div>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div id="iconGrid" class="icon-grid">
                                    <!-- Icons will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="form-group">
                            <label class="form-label">السعر الأدنى (ريال)</label>
                            <div class="price-input">
                                <input type="number" id="priceMin" name="priceMin" class="form-input" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">السعر الأعلى (ريال)</label>
                            <div class="price-input">
                                <input type="number" id="priceMax" name="priceMax" class="form-input" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <!-- Duration and Order -->
                        <div class="form-group">
                            <label class="form-label">مدة التنفيذ</label>
                            <select id="duration" name="duration" class="form-select">
                                <option value="">غير محدد</option>
                                <option value="ساعة">ساعة واحدة</option>
                                <option value="ساعتين">ساعتين</option>
                                <option value="3-5 ساعات">3-5 ساعات</option>
                                <option value="يوم">يوم واحد</option>
                                <option value="يومين">يومين</option>
                                <option value="3-7 أيام">3-7 أيام</option>
                                <option value="أسبوع">أسبوع</option>
                                <option value="أسبوعين">أسبوعين</option>
                                <option value="شهر">شهر</option>
                                <option value="مخصص">مخصص</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">ترتيب العرض</label>
                            <input type="number" id="displayOrder" name="displayOrder" class="form-input" min="0" value="0">
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label class="form-label">حالة الخدمة</label>
                            <select id="status" name="status" class="form-select">
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">
                    <i class="fas fa-times"></i> إلغاء
                </button>
                <button type="button" class="btn btn-primary" onclick="saveService()">
                    <i class="fas fa-save"></i> حفظ الخدمة
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script>
        let allServices = [];
        let currentServiceId = null;

        // FontAwesome icons for services
        const serviceIcons = [
            'fa-star', 'fa-code', 'fa-paint-brush', 'fa-camera', 'fa-mobile-alt',
            'fa-shopping-cart', 'fa-search', 'fa-chart-line', 'fa-users', 'fa-cog',
            'fa-rocket', 'fa-lightbulb', 'fa-globe', 'fa-lock', 'fa-shield-alt',
            'fa-heart', 'fa-smile', 'fa-thumbs-up', 'fa-check-circle', 'fa-award',
            'fa-briefcase', 'fa-graduation-cap', 'fa-music', 'fa-gamepad', 'fa-utensils',
            'fa-home', 'fa-car', 'fa-plane', 'fa-ship', 'fa-bus'
        ];

        // Initialize page
        $(document).ready(function() {
            loadServices();
            initializeIconPicker();
        });

        // Load services from API
        function loadServices() {
            $('#servicesGrid').html('<div class="loading">جاري تحميل الخدمات...</div>');

            $.get('/admin/ajax/services.php?action=list', function(response) {
                if (response.success) {
                    allServices = response.data;
                    updateStatistics();
                    renderServices();
                } else {
                    $('#servicesGrid').html('<div class="empty-state"><i class="fas fa-exclamation-triangle"></i><h3>خطأ في تحميل الخدمات</h3><p>' + response.message + '</p></div>');
                }
            }).fail(function() {
                $('#servicesGrid').html('<div class="empty-state"><i class="fas fa-exclamation-triangle"></i><h3>خطأ في الاتصال</h3><p>تعذر الاتصال بالخادم</p></div>');
            });
        }

        // Update statistics
        function updateStatistics() {
            const total = allServices.length;
            const active = allServices.filter(s => s.status === 'active').length;
            const inactive = total - active;

            $('#totalServices').text(total);
            $('#activeServices').text(active);
            $('#inactiveServices').text(inactive);
        }

        // Render services grid
        function renderServices() {
            const filter = $('#statusFilter').val();
            let filteredServices = allServices;

            if (filter !== 'all') {
                filteredServices = allServices.filter(s => s.status === filter);
            }

            if (filteredServices.length === 0) {
                $('#servicesGrid').html('<div class="empty-state"><i class="fas fa-concierge-bell"></i><h3>لا توجد خدمات</h3><p>لم يتم العثور على خدمات تطابق المعايير المحددة</p></div>');
                return;
            }

            const servicesHtml = filteredServices.map(service => `
                <div class="service-card ${service.status}" data-id="${service.id}">
                    <div class="service-header">
                        <div class="service-icon">
                            <i class="fas ${service.icon}"></i>
                        </div>
                        <div>
                            <div class="service-title">${service.title}</div>
                            <div class="service-meta">
                                <span><i class="fas fa-hashtag"></i> ${service.display_order}</span>
                                <span><i class="fas fa-${service.status === 'active' ? 'check-circle' : 'pause-circle'}"></i> ${service.status === 'active' ? 'نشط' : 'غير نشط'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="service-description">${service.description}</div>

                    <div class="service-details">
                        ${service.price_min || service.price_max ? `
                            <div class="detail-item">
                                <div class="detail-label">السعر</div>
                                <div class="detail-value">
                                    ${service.price_min && service.price_max ?
                                        `${service.price_min} - ${service.price_max} ريال` :
                                        service.price_min ? `من ${service.price_min} ريال` :
                                        service.price_max ? `حتى ${service.price_max} ريال` : 'غير محدد'
                                    }
                                </div>
                            </div>
                        ` : ''}
                        ${service.duration ? `
                            <div class="detail-item">
                                <div class="detail-label">المدة</div>
                                <div class="detail-value">${service.duration}</div>
                            </div>
                        ` : ''}
                    </div>

                    <div class="service-actions">
                        <button class="btn btn-warning btn-sm" onclick="editService(${service.id})">
                            <i class="fas fa-edit"></i> تعديل
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteService(${service.id})">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                </div>
            `).join('');

            $('#servicesGrid').html(servicesHtml);
        }

        // Filter services
        function filterServices() {
            renderServices();
        }

        // Initialize icon picker
        function initializeIconPicker() {
            const iconGrid = $('#iconGrid');
            serviceIcons.forEach(icon => {
                iconGrid.append(`
                    <div class="icon-option" data-icon="${icon}" onclick="selectIcon('${icon}')">
                        <i class="fas ${icon}"></i>
                    </div>
                `);
            });
        }

        // Toggle icon picker
        function toggleIconPicker() {
            $('#iconGrid').slideToggle(300);
        }

        // Select icon
        function selectIcon(icon) {
            $('#selectedIcon').attr('class', `fas ${icon}`);
            $('#selectedIconName').text(icon);
            $('#icon').val(icon);
            $('.icon-option').removeClass('selected');
            $(`.icon-option[data-icon="${icon}"]`).addClass('selected');
            toggleIconPicker();
        }

        // Show add modal
        function showAddModal() {
            currentServiceId = null;
            $('#modalTitle').html('<i class="fas fa-plus"></i> إضافة خدمة جديدة');
            $('#serviceForm')[0].reset();
            $('#serviceId').val('');
            $('#selectedIcon').attr('class', 'fas fa-star');
            $('#selectedIconName').text('fa-star');
            $('.form-group').removeClass('error');
            $('#serviceModal').fadeIn(300);
        }

        // Edit service
        function editService(id) {
            const service = allServices.find(s => s.id == id);
            if (!service) return;

            currentServiceId = id;
            $('#modalTitle').html('<i class="fas fa-edit"></i> تعديل الخدمة');
            $('#serviceId').val(service.id);
            $('#title').val(service.title);
            $('#description').val(service.description);
            $('#icon').val(service.icon);
            $('#priceMin').val(service.price_min || '');
            $('#priceMax').val(service.price_max || '');
            $('#duration').val(service.duration || '');
            $('#displayOrder').val(service.display_order);
            $('#status').val(service.status);

            // Update icon display
            $('#selectedIcon').attr('class', `fas ${service.icon}`);
            $('#selectedIconName').text(service.icon);
            $('.icon-option').removeClass('selected');
            $(`.icon-option[data-icon="${service.icon}"]`).addClass('selected');

            $('.form-group').removeClass('error');
            $('#serviceModal').fadeIn(300);
        }

        // Save service
        function saveService() {
            if (!validateForm()) return;

            const formData = new FormData($('#serviceForm')[0]);
            const action = currentServiceId ? 'update' : 'create';
            formData.append('action', action);

            // Show loading
            const saveBtn = $('.modal-actions .btn-primary');
            const originalText = saveBtn.html();
            saveBtn.html('<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...').prop('disabled', true);

            $.ajax({
                url: '/admin/ajax/services.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح!',
                            text: currentServiceId ? 'تم تحديث الخدمة بنجاح' : 'تم إضافة الخدمة بنجاح',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        closeModal();
                        loadServices();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: response.message || 'حدث خطأ غير متوقع'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ في الاتصال',
                        text: 'تعذر الاتصال بالخادم'
                    });
                },
                complete: function() {
                    saveBtn.html(originalText).prop('disabled', false);
                }
            });
        }

        // Validate form
        function validateForm() {
            let isValid = true;
            $('.form-group').removeClass('error');

            // Title validation
            const title = $('#title').val().trim();
            if (!title || title.length < 3) {
                $('#title').closest('.form-group').addClass('error');
                isValid = false;
            }

            // Description validation
            const description = $('#description').val().trim();
            if (!description || description.length < 10) {
                $('#description').closest('.form-group').addClass('error');
                isValid = false;
            }

            // Price validation
            const priceMin = parseFloat($('#priceMin').val());
            const priceMax = parseFloat($('#priceMax').val());
            if (priceMin && priceMax && priceMin >= priceMax) {
                $('#priceMin').closest('.form-group').addClass('error');
                $('#priceMax').closest('.form-group').addClass('error');
                isValid = false;
            }

            return isValid;
        }

        // Delete service
        function deleteService(id) {
            const service = allServices.find(s => s.id == id);
            if (!service) return;

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: `سيتم حذف الخدمة "${service.title}" نهائياً!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('/admin/ajax/services.php', { action: 'delete', id: id }, function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف',
                                text: 'تم حذف الخدمة بنجاح',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            loadServices();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: response.message || 'فشل في حذف الخدمة'
                            });
                        }
                    }).fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ في الاتصال',
                            text: 'تعذر الاتصال بالخادم'
                        });
                    });
                }
            });
        }

        // Close modal
        function closeModal() {
            $('#serviceModal').fadeOut(300);
            $('#serviceForm')[0].reset();
            $('.form-group').removeClass('error');
            $('#iconGrid').hide();
        }

        // Refresh services
        function refreshServices() {
            loadServices();
        }

        // Close modal when clicking outside
        $(document).on('click', '.modal', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal on escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#serviceModal').is(':visible')) {
                closeModal();
            }
        });
    </script>
