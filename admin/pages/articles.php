<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المقالات - لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="/static/js/jquery-3.6.0.min.js"></script>
    <script src="/static/js/sweetalert2.all.min.js"></script>
    <style>
        :root {
            --site-primary: #08137b;
            --site-secondary: #4f09a7;
            --site-gold: #c5a47e;
            --site-bg: #f8f9fa;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', sans-serif; background: var(--site-bg); direction: rtl; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }

        .articles-container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .articles-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .btn-add { padding: 12px 25px; background: linear-gradient(135deg, var(--site-primary), var(--site-secondary)); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: transform 0.2s; }
        .btn-add:hover { transform: translateY(-2px); }
        .articles-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .article-card { background: var(--site-bg); padding: 20px; border-radius: 10px; transition: all 0.3s; border: 1px solid rgba(8,19,123,0.08); }
        .article-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); border-color: var(--site-primary); }
        .article-title { font-size: 18px; font-weight: 600; color: var(--site-primary); margin-bottom: 10px; }
        .article-category { display: inline-block; padding: 5px 12px; background: linear-gradient(135deg, var(--site-gold), #d4af37); color: white; border-radius: 15px; font-size: 12px; margin-bottom: 10px; }
        .article-actions { display: flex; gap: 10px; margin-top: 15px; }
        .btn-edit, .btn-delete { padding: 8px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; transition: all 0.2s; }
        .btn-edit { background: #3498db; color: white; }
        .btn-edit:hover { background: #2980b9; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-delete:hover { background: #c0392b; }

        /* Enhanced Modal Styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); }
        .modal-content { background: white; margin: 2% auto; padding: 0; width: 90%; max-width: 1000px; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); max-height: 90vh; overflow-y: auto; }
        .modal-header { background: linear-gradient(135deg, var(--site-primary), var(--site-secondary)); color: white; padding: 20px 30px; border-radius: 15px 15px 0 0; display: flex; justify-content: space-between; align-items: center; }
        .modal-header h2 { margin: 0; font-size: 24px; }
        .close-btn { background: none; border: none; color: white; font-size: 28px; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background 0.2s; }
        .close-btn:hover { background: rgba(255,255,255,0.2); }

        /* Enhanced Form Styles */
        .article-form { padding: 30px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-section { background: var(--site-bg); padding: 20px; border-radius: 10px; border: 1px solid rgba(8,19,123,0.08); }
        .form-section h3 { color: var(--site-primary); margin-bottom: 15px; font-size: 18px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--site-primary); }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-family: 'Tajawal', sans-serif; transition: border-color 0.2s; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--site-primary); box-shadow: 0 0 0 3px rgba(8,19,123,0.1); }

        /* Content Editor Section */
        .content-section { grid-column: 1 / -1; }
        .editor-toolbar { background: #f8f9fa; padding: 10px; border-radius: 8px 8px 0 0; border: 1px solid #e5e7eb; border-bottom: none; }
        .word-count { display: flex; gap: 20px; color: #666; font-size: 14px; }
        .word-count i { margin-right: 5px; }

        /* Image Upload Section */
        .image-upload-area { border: 2px dashed #e5e7eb; border-radius: 8px; padding: 20px; text-align: center; transition: all 0.2s; cursor: pointer; }
        .image-upload-area:hover { border-color: var(--site-primary); background: rgba(8,19,123,0.02); }
        .image-upload-area.dragover { border-color: var(--site-gold); background: rgba(197,164,126,0.05); }
        .upload-icon { font-size: 48px; color: #ccc; margin-bottom: 10px; }
        .image-preview { max-width: 100%; max-height: 200px; border-radius: 8px; margin-top: 10px; }

        /* Form Actions */
        .form-actions { grid-column: 1 / -1; display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
        .btn-submit { padding: 12px 30px; background: linear-gradient(135deg, var(--site-primary), var(--site-secondary)); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px; transition: transform 0.2s; }
        .btn-submit:hover { transform: translateY(-2px); }
        .btn-cancel { padding: 12px 30px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.2s; }
        .btn-cancel:hover { background: #5a6268; }

        /* Validation Styles */
        .form-group.error input, .form-group.error textarea, .form-group.error select { border-color: #dc3545; }
        .error-message { color: #dc3545; font-size: 14px; margin-top: 5px; display: none; }
        .form-group.error .error-message { display: block; }

        /* Loading States */
        .btn-submit.loading { opacity: 0.7; cursor: not-allowed; }
        .btn-submit.loading::after { content: ' جاري الحفظ...'; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .modal-content { width: 95%; margin: 5% auto; }
            .article-form { padding: 20px; }
            .articles-header { flex-direction: column; gap: 15px; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        require_once '../../config.php';

        if (!isAdmin()) {
            header('Location: /admin/login.php');
            exit;
        }

        $stmt = $db->prepare("SELECT a.*, u.full_name as author FROM articles a JOIN admin_users u ON a.author_id = u.id ORDER BY a.created_at DESC");
        $stmt->execute();
        $articles = $stmt->fetchAll();
        ?>

        <div class="articles-container">
            <div class="articles-header">
                <h2>إدارة المقالات</h2>
                <button class="btn-add" onclick="showAddForm()"><i class="fas fa-plus"></i> إضافة مقال</button>
            </div>

            <div class="articles-grid" id="articlesGrid">
                <?php foreach ($articles as $article): ?>
                    <div class="article-card">
                        <div class="article-category"><?php echo htmlspecialchars($article['category']); ?></div>
                        <div class="article-title"><?php echo htmlspecialchars($article['title']); ?></div>
                        <p><?php echo htmlspecialchars(mb_substr($article['excerpt'] ?? '', 0, 100)); ?>...</p>
                        <div style="color: #999; font-size: 14px; margin-top: 10px;">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($article['author']); ?> |
                            <i class="fas fa-eye"></i> <?php echo $article['views'] ?? 0; ?> مشاهدة
                        </div>
                        <div class="article-actions">
                            <button class="btn-edit" onclick="editArticle(<?php echo $article['id']; ?>)">
                                <i class="fas fa-edit"></i> تعديل
                            </button>
                            <button class="btn-delete" onclick="deleteArticle(<?php echo $article['id']; ?>)">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="articleModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modalTitle">إضافة مقال جديد</h2>
                    <span class="close-btn" onclick="closeModal('articleModal')">&times;</span>
                </div>
                <form id="articleForm" class="article-form">
                    <input type="hidden" id="articleId" name="id">
                    <div class="form-grid">
                        <div class="form-section">
                            <h3>معلومات أساسية</h3>
                            <div class="form-group">
                                <label>العنوان *</label>
                                <input type="text" id="title" name="title" required>
                                <div class="error-message">هذا الحقل مطلوب</div>
                            </div>
                            <div class="form-group">
                                <label>الرابط (slug) *</label>
                                <input type="text" id="slug" name="slug" required>
                                <div class="error-message">هذا الحقل مطلوب</div>
                            </div>
                            <div class="form-group">
                                <label>التصنيف</label>
                                <select id="category" name="category">
                                    <option value="article">مقالات</option>
                                    <option value="book">كتب</option>
                                    <option value="course">دورات</option>
                                    <option value="service">خدمات</option>
                                    <option value="news">أخبار</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>الوسم (Badge)</label>
                                <input type="text" id="badge" name="badge" placeholder="جديد، قريباً، إطلاق">
                            </div>
                            <div class="form-group">
                                <label>الحالة</label>
                                <select id="status" name="status">
                                    <option value="draft">مسودة</option>
                                    <option value="published">منشور</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>صورة المقال</h3>
                            <div class="form-group">
                                <label>رابط الصورة</label>
                                <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                            </div>
                            <div class="image-upload-area" id="imageUploadArea">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <p>اسحب وأفلت الصورة هنا أو انقر للاختيار</p>
                                <input type="file" id="imageFile" accept="image/*" style="display: none;">
                                <button type="button" id="uploadImageBtn" class="btn-edit" style="margin-top: 10px;">رفع الصورة</button>
                            </div>
                            <img id="imagePreview" src="" alt="preview" class="image-preview" style="display: none;">
                        </div>

                        <div class="form-section content-section">
                            <h3>محتوى المقال</h3>
                            <div class="form-group">
                                <label>المقتطف</label>
                                <textarea id="excerpt" name="excerpt" rows="3" placeholder="وصف مختصر للمقال..."></textarea>
                            </div>
                            <div class="editor-toolbar">
                                <div class="word-count">
                                    <span><i class="fas fa-file-alt"></i> الكلمات: <span id="wordCount">0</span></span>
                                    <span><i class="fas fa-clock"></i> وقت القراءة: <span id="readingTime">0</span> دقيقة</span>
                                </div>
                                <button type="button" id="previewBtn" class="btn-edit" style="float: left;">معاينة</button>
                            </div>
                            <div class="form-group">
                                <textarea id="content" name="content" rows="12" required placeholder="اكتب محتوى المقال هنا..."></textarea>
                                <div class="error-message">هذا الحقل مطلوب</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeModal('articleModal')">إلغاء</button>
                        <button type="submit" class="btn-submit">حفظ المقال</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let tinyMceReady = false;
        const tmceScript = document.createElement('script');
        tmceScript.src = 'https://cdn.jsdelivr.net/npm/tinymce@6.8.0/tinymce.min.js';
        tmceScript.referrerPolicy = 'origin';
        document.head.appendChild(tmceScript);
        tmceScript.onload = () => {
            tinymce.init({
                selector: '#content',
                height: 400,
                menubar: false,
                plugins: 'link image code lists media table paste imagetools wordcount fullscreen preview',
                toolbar: 'undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | code fullscreen preview',
                automatic_uploads: true,
                images_upload_url: '/admin/ajax/upload_image.php',
                images_upload_handler: function (blobInfo, success, failure) {
                    var xhr = new XMLHttpRequest();
                    xhr.withCredentials = true;
                    xhr.open('POST', '/admin/ajax/upload_image.php');
                    xhr.onload = function() {
                        if (xhr.status !== 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        var json = JSON.parse(xhr.responseText);
                        if (!json || (!json.location && !json.url)) {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        success(json.location || json.url);
                    };
                    var formData = new FormData();
                    formData.append('image', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                },
                init_instance_callback: function() {
                    tinyMceReady = true;
                },
                setup: function(editor) {
                    editor.on('change keyup', function() {
                        updateReadingStats();
                    });
                }
            });
        };

        function getTinyContent() {
            return (typeof tinymce !== 'undefined' && tinymce.get('content')) ? tinymce.get('content').getContent() : $('#content').val();
        }

        function setTinyContent(content) {
            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                tinymce.get('content').setContent(content);
            } else {
                $('#content').val(content);
            }
        }

        function showAddForm() {
            $('#modalTitle').text('إضافة مقال جديد');
            $('#articleForm')[0].reset();
            $('#articleId').val('');
            setTinyContent('');
            $('#imagePreview').hide();
            $('#articleModal').show();
            // Reset validation
            $('.form-group').removeClass('error');
            $('.error-message').hide();
        }

        function editArticle(id) {
            $.get('/admin/ajax/articles.php?action=get&id=' + id, function(response) {
                if (response.success) {
                    const article = response.data;
                    $('#modalTitle').text('تعديل المقال');
                    $('#articleId').val(article.id);
                    $('#title').val(article.title);
                    $('#slug').val(article.slug);
                    $('#excerpt').val(article.excerpt);
                    setTinyContent(article.content);
                    $('#category').val(article.category);
                    $('#badge').val(article.badge);
                    $('#image_url').val(article.image_url);
                    $('#status').val(article.status);

                    if (article.image_url) {
                        $('#imagePreview').attr('src', article.image_url).show();
                    } else {
                        $('#imagePreview').hide();
                    }

                    $('#articleModal').show();
                    updateReadingStats();
                }
            });
        }

        function closeModal(modalId) {
            $('#' + modalId).hide();
        }

        // Form validation
        function validateForm() {
            let isValid = true;
            $('.form-group').removeClass('error');
            $('.error-message').hide();

            const title = $('#title').val().trim();
            const slug = $('#slug').val().trim();
            const content = getTinyContent().trim();

            if (!title) {
                $('#title').closest('.form-group').addClass('error');
                isValid = false;
            }

            if (!slug) {
                $('#slug').closest('.form-group').addClass('error');
                isValid = false;
            }

            if (!content) {
                $('#content').closest('.form-group').addClass('error');
                isValid = false;
            }

            return isValid;
        }

        $('#articleForm').on('submit', function(e) {
            e.preventDefault();

            if (!validateForm()) {
                Swal.fire('خطأ', 'يرجى ملء جميع الحقول المطلوبة', 'error');
                return;
            }

            const $submitBtn = $('.btn-submit');
            $submitBtn.addClass('loading').prop('disabled', true);

            $('#content').val(getTinyContent());
            const formData = $(this).serialize();
            const action = $('#articleId').val() ? 'update' : 'create';

            $.post('/admin/ajax/articles.php', formData + '&action=' + action, function(response) {
                $submitBtn.removeClass('loading').prop('disabled', false);

                if (response.success) {
                    Swal.fire('نجح', response.message, 'success');
                    $('#articleModal').hide();
                    refreshArticles();
                } else {
                    Swal.fire('خطأ', response.message, 'error');
                }
            }).fail(function() {
                $submitBtn.removeClass('loading').prop('disabled', false);
                Swal.fire('خطأ', 'حدث خطأ في الإرسال', 'error');
            });
        });

        function refreshArticles(){
            $.get('/admin/ajax/articles.php?action=list', function(response){
                if (!response.success) return;
                const grid = $('#articlesGrid'); grid.empty();
                response.data.forEach(article => {
                    const card = $(`
                        <div class="article-card">
                            <div class="article-category">${article.category}</div>
                            <div class="article-title">${article.title}</div>
                            <p>${(article.excerpt||'').substring(0,100)}...</p>
                            <div style="color: #999; font-size: 14px; margin-top: 10px;">
                                <i class="fas fa-user"></i> ${article.author} |
                                <i class="fas fa-eye"></i> ${article.reading_time || '—'} دقيقة تقديرية
                            </div>
                            <div class="article-actions">
                                <button class="btn-edit" onclick="editArticle(${article.id})"><i class="fas fa-edit"></i> تعديل</button>
                                <button class="btn-delete" onclick="deleteArticle(${article.id})"><i class="fas fa-trash"></i> حذف</button>
                            </div>
                        </div>
                    `);
                    grid.append(card);
                });
            }, 'json');
        }

        function deleteArticle(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف المقال نهائياً!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('/admin/ajax/articles.php', { action: 'delete', id: id }, function(response) {
                        if (response.success) {
                            Swal.fire('تم', 'تم حذف المقال', 'success');
                            refreshArticles();
                        }
                    });
                }
            });
        }

        // Auto-generate slug from title
        $('#title').on('input', function() {
            if (!$('#articleId').val()) {
                const slug = $(this).val().toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
                $('#slug').val(slug);
            }
        });

        // Close modal when clicking outside
        $(document).on('click', '.modal', function(e) {
            if (e.target === this) {
                $(this).hide();
            }
        });

        // Drag and drop for images
        const imageUploadArea = document.getElementById('imageUploadArea');
        const imageFileInput = document.getElementById('imageFile');
        const imagePreview = document.getElementById('imagePreview');

        imageUploadArea.addEventListener('click', () => imageFileInput.click());

        imageUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            imageUploadArea.classList.add('dragover');
        });

        imageUploadArea.addEventListener('dragleave', () => {
            imageUploadArea.classList.remove('dragover');
        });

        imageUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            imageUploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleImageFile(files[0]);
            }
        });

        imageFileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleImageFile(e.target.files[0]);
            }
        });

        function handleImageFile(file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Auto-save draft (optional enhancement)
        let autoSaveTimer;
        function startAutoSave() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                if ($('#title').val() || getTinyContent()) {
                    // Implement auto-save logic here
                    console.log('Auto-saving draft...');
                }
            }, 30000); // Save every 30 seconds
        }

        $('#title, #content').on('input', startAutoSave);

        // Preview button
        document.addEventListener('click', function(e){
            if (e.target && e.target.id === 'previewBtn'){
                const content = getTinyContent();
                const title = $('#title').val();
                const win = window.open('', '_blank');
                win.document.write(`
                    <html>
                    <head>
                        <meta charset="utf-8">
                        <title>معاينة: ${title}</title>
                        <style>body{font-family:Arial,sans-serif;max-width:800px;margin:20px auto;padding:20px;}</style>
                    </head>
                    <body>
                        <h1>${title}</h1>
                        ${content}
                    </body>
                    </html>
                `);
            }
        });

        // Update reading stats live
        function updateReadingStats(){
            const content = getTinyContent();
            const textContent = content.replace(/<[^>]*>/g, ''); // Remove HTML tags
            const words = textContent.trim() ? textContent.trim().split(/\s+/).length : 0;
            const minutes = Math.max(1, Math.ceil(words / 200));
            document.getElementById('wordCount').textContent = words;
            document.getElementById('readingTime').textContent = minutes;
        }
    </script>
</body>
</html>
                </div>
                
                <div class="form-section">
                    <h3>صورة المقال</h3>
                    <div class="form-group">
                        <label>رابط الصورة</label>
                        <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="image-upload-area" id="imageUploadArea">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <p>اسحب وأفلت الصورة هنا أو انقر للاختيار</p>
                        <input type="file" id="imageFile" accept="image/*" style="display: none;">
                        <button type="button" id="uploadImageBtn" class="btn-edit" style="margin-top: 10px;">رفع الصورة</button>
                    </div>
                    <img id="imagePreview" src="" alt="preview" class="image-preview" style="display: none;">
                </div>
                
                <div class="form-section content-section">
                    <h3>محتوى المقال</h3>
                    <div class="form-group">
                        <label>المقتطف</label>
                        <textarea id="excerpt" name="excerpt" rows="3" placeholder="وصف مختصر للمقال..."></textarea>
                    </div>
                    <div class="editor-toolbar">
                        <div class="word-count">
                            <span><i class="fas fa-file-alt"></i> الكلمات: <span id="wordCount">0</span></span>
                            <span><i class="fas fa-clock"></i> وقت القراءة: <span id="readingTime">0</span> دقيقة</span>
                        </div>
                        <button type="button" id="previewBtn" class="btn-edit" style="float: left;">معاينة</button>
                    </div>
                    <div class="form-group">
                        <textarea id="content" name="content" rows="12" required placeholder="اكتب محتوى المقال هنا..."></textarea>
                        <div class="error-message">هذا الحقل مطلوب</div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeModal('articleModal')">إلغاء</button>
                <button type="submit" class="btn-submit">حفظ المقال</button>
            </div>
let tinyMceReady = false;
const tmceScript = document.createElement('script');
tmceScript.src = 'https://cdn.jsdelivr.net/npm/tinymce@6.8.0/tinymce.min.js';
tmceScript.referrerPolicy = 'origin';
document.head.appendChild(tmceScript);
tmceScript.onload = () => {
    tinymce.init({ 
        selector: '#content',
        height: 400,
        menubar: false,
        plugins: 'link image code lists media table paste imagetools wordcount fullscreen preview',
        toolbar: 'undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | code fullscreen preview',
        automatic_uploads: true,
        images_upload_url: '/admin/ajax/upload_image.php',
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr = new XMLHttpRequest();
            xhr.withCredentials = true;
            xhr.open('POST', '/admin/ajax/upload_image.php');
            xhr.onload = function() {
                if (xhr.status !== 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                var json = JSON.parse(xhr.responseText);
                if (!json || (!json.location && !json.url)) {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.location || json.url);
            };
            var formData = new FormData();
            formData.append('image', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        },
        init_instance_callback: function() {
            tinyMceReady = true;
        },
        setup: function(editor) {
            editor.on('change keyup', function() {
                updateReadingStats();
            });
        }
    });
};

function getTinyContent() {
    return (typeof tinymce !== 'undefined' && tinymce.get('content')) ? tinymce.get('content').getContent() : $('#content').val();
}

function setTinyContent(content) {
    if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
        tinymce.get('content').setContent(content);
    } else {
        $('#content').val(content);
    }
}

function showAddForm() {
    $('#modalTitle').text('إضافة مقال جديد');
    $('#articleForm')[0].reset();
    $('#articleId').val('');
    setTinyContent('');
    $('#imagePreview').hide();
    $('#articleModal').show();
    // Reset validation
    $('.form-group').removeClass('error');
    $('.error-message').hide();
}

function editArticle(id) {
    $.get('/admin/ajax/articles.php?action=get&id=' + id, function(response) {
        if (response.success) {
            const article = response.data;
            $('#modalTitle').text('تعديل المقال');
            $('#articleId').val(article.id);
            $('#title').val(article.title);
            $('#slug').val(article.slug);
            $('#excerpt').val(article.excerpt);
            setTinyContent(article.content);
            $('#category').val(article.category);
            $('#badge').val(article.badge);
            $('#image_url').val(article.image_url);
            $('#status').val(article.status);
            
            if (article.image_url) {
                $('#imagePreview').attr('src', article.image_url).show();
            } else {
                $('#imagePreview').hide();
            }
            
            $('#articleModal').show();
            updateReadingStats();
        }
    });
}

function closeModal(modalId) {
    $('#' + modalId).hide();
}

// Form validation
function validateForm() {
    let isValid = true;
    $('.form-group').removeClass('error');
    $('.error-message').hide();
    
    const title = $('#title').val().trim();
    const slug = $('#slug').val().trim();
    const content = getTinyContent().trim();
    
    if (!title) {
        $('#title').closest('.form-group').addClass('error');
        isValid = false;
    }
    
    if (!slug) {
        $('#slug').closest('.form-group').addClass('error');
        isValid = false;
    }
    
    if (!content) {
        $('#content').closest('.form-group').addClass('error');
        isValid = false;
    }
    
    return isValid;
}

$('#articleForm').on('submit', function(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        Swal.fire('خطأ', 'يرجى ملء جميع الحقول المطلوبة', 'error');
        return;
    }
    
    const $submitBtn = $('.btn-submit');
    $submitBtn.addClass('loading').prop('disabled', true);
    
    $('#content').val(getTinyContent());
    const formData = $(this).serialize();
    const action = $('#articleId').val() ? 'update' : 'create';
    
    $.post('/admin/ajax/articles.php', formData + '&action=' + action, function(response) {
        $submitBtn.removeClass('loading').prop('disabled', false);
        
        if (response.success) {
            Swal.fire('نجح', response.message, 'success');
            $('#articleModal').hide();
            refreshArticles();
        } else {
            Swal.fire('خطأ', response.message, 'error');
        }
    }).fail(function() {
        $submitBtn.removeClass('loading').prop('disabled', false);
        Swal.fire('خطأ', 'حدث خطأ في الإرسال', 'error');
    });
});

function refreshArticles(){
    $.get('/admin/ajax/articles.php?action=list', function(response){
        if (!response.success) return;
        const grid = $('#articlesGrid'); grid.empty();
        response.data.forEach(article => {
            const card = $(`\
                <div class="article-card">\
                    <div class="article-category">${article.category}</div>\
                    <div class="article-title">${article.title}</div>\
                    <p>${(article.excerpt||'').substring(0,100)}...</p>\
                    <div style="color: #999; font-size: 14px; margin-top: 10px;">\
                        <i class="fas fa-user"></i> ${article.author} |\
                        <i class="fas fa-eye"></i> ${article.reading_time || '—'} دقيقة تقديرية\
                    </div>\
                    <div class="article-actions">\
                        <button class="btn-edit" onclick="editArticle(${article.id})"><i class="fas fa-edit"></i> تعديل</button>\
                        <button class="btn-delete" onclick="deleteArticle(${article.id})"><i class="fas fa-trash"></i> حذف</button>\
                    </div>\
                </div>
            `);
            grid.append(card);
        });
    }, 'json');
}

function deleteArticle(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'سيتم حذف المقال نهائياً!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('/admin/ajax/articles.php', { action: 'delete', id: id }, function(response) {
                if (response.success) {
                    Swal.fire('تم', 'تم حذف المقال', 'success');
                    refreshArticles();
                }
            });
        }
    });
}

// Auto-generate slug from title
$('#title').on('input', function() {
    if (!$('#articleId').val()) {
        const slug = $(this).val().toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
        $('#slug').val(slug);
    }
});

// Close modal when clicking outside
$(document).on('click', '.modal', function(e) {
    if (e.target === this) {
        $(this).hide();
    }
});

// Drag and drop for images
const imageUploadArea = document.getElementById('imageUploadArea');
const imageFileInput = document.getElementById('imageFile');
const imagePreview = document.getElementById('imagePreview');

imageUploadArea.addEventListener('click', () => imageFileInput.click());

imageUploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    imageUploadArea.classList.add('dragover');
});

imageUploadArea.addEventListener('dragleave', () => {
    imageUploadArea.classList.remove('dragover');
});

imageUploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    imageUploadArea.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleImageFile(files[0]);
    }
});

imageFileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleImageFile(e.target.files[0]);
    }
});

function handleImageFile(file) {
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

// Auto-save draft (optional enhancement)
let autoSaveTimer;
function startAutoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        if ($('#title').val() || getTinyContent()) {
            // Implement auto-save logic here
            console.log('Auto-saving draft...');
        }
    }, 30000); // Save every 30 seconds
}

$('#title, #content').on('input', startAutoSave);

// Preview button
document.addEventListener('click', function(e){
    if (e.target && e.target.id === 'previewBtn'){
        const content = getTinyContent();
        const title = $('#title').val();
        const win = window.open('', '_blank');
        win.document.write(`
            <html>
            <head>
                <meta charset="utf-8">
                <title>معاينة: ${title}</title>
                <style>body{font-family:Arial,sans-serif;max-width:800px;margin:20px auto;padding:20px;}</style>
            </head>
            <body>
                <h1>${title}</h1>
                ${content}
            </body>
            </html>
        `);
    }
});

// Update reading stats live
function updateReadingStats(){
    const content = getTinyContent();
    const textContent = content.replace(/<[^>]*>/g, ''); // Remove HTML tags
    const words = textContent.trim() ? textContent.trim().split(/\s+/).length : 0;
    const minutes = Math.max(1, Math.ceil(words / 200));
    document.getElementById('wordCount').textContent = words;
    document.getElementById('readingTime').textContent = minutes;
}

setInterval(updateReadingStats, 1000);
</script>
