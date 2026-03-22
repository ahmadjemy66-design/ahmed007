<?php
// Products Management for E-commerce

$productsStmt = $db->prepare("
    SELECT p.id, p.name_ar, p.slug, p.price, p.stock_quantity, p.is_active, p.rating_average, p.rating_count, c.name_ar as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.created_at DESC
    LIMIT 500
");
$productsStmt->execute();
$products = $productsStmt->fetchAll();

$categoriesStmt = $db->prepare("SELECT id, name_ar FROM categories WHERE status = 'active' ORDER BY display_order");
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll();
?>

<style>
.products-container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
.products-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.btn-add { padding: 12px 25px; background: linear-gradient(135deg, var(--site-primary), var(--site-secondary)); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: transform 0.2s; }
.btn-add:hover { transform: translateY(-2px); }
.products-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.products-table th { background: var(--site-bg); padding: 15px; text-align: right; font-weight: 600; color: var(--site-primary); border-bottom: 2px solid rgba(8,19,123,0.08); }
.products-table td { padding: 15px; border-bottom: 1px solid rgba(8,19,123,0.08); }
.products-table tr:hover { background: #f9f9f9; }
.price-badge { padding: 5px 10px; background: #27ae60; color: white; border-radius: 5px; font-weight: 600; }
.stock-badge { padding: 5px 10px; border-radius: 5px; }
.stock-badge.low { background: #e74c3c; color: white; }
.stock-badge.medium { background: #f39c12; color: white; }
.stock-badge.high { background: #27ae60; color: white; }
.rating-badge { color: #f39c12; }
.action-buttons { display: flex; gap: 10px; }
.btn-edit, .btn-delete { padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; transition: all 0.2s; }
.btn-edit { background: #3498db; color: white; }
.btn-edit:hover { background: #2980b9; }
.btn-delete { background: #e74c3c; color: white; }
.btn-delete:hover { background: #c0392b; }

/* Enhanced Form Styles */
.product-form { background: var(--site-bg); padding: 30px; border-radius: 10px; margin-bottom: 20px; border: 1px solid rgba(8,19,123,0.08); display: none; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
.form-section { background: white; padding: 20px; border-radius: 10px; border: 1px solid rgba(8,19,123,0.08); }
.form-section h3 { color: var(--site-primary); margin-bottom: 15px; font-size: 18px; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--site-primary); }
.form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-family: 'Cairo', sans-serif; transition: border-color 0.2s; }
.form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--site-primary); box-shadow: 0 0 0 3px rgba(8,19,123,0.1); }
.btn-save { padding: 12px 30px; background: linear-gradient(135deg, var(--site-primary), var(--site-secondary)); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px; transition: transform 0.2s; }
.btn-save:hover { transform: translateY(-2px); }
.btn-cancel { padding: 12px 30px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.2s; }
.btn-cancel:hover { background: #5a6268; }

/* Validation Styles */
.form-group.error input, .form-group.error textarea, .form-group.error select { border-color: #dc3545; }
.error-message { color: #dc3545; font-size: 14px; margin-top: 5px; display: none; }
.form-group.error .error-message { display: block; }

/* Loading States */
.btn-save.loading { opacity: 0.7; cursor: not-allowed; }

/* Responsive Design */
@media (max-width: 768px) {
    .products-header { flex-direction: column; gap: 15px; text-align: center; }
    .form-grid { grid-template-columns: 1fr; }
}
</style>

<div class="products-container">
    <div class="products-header">
        <h2>إدارة المنتجات</h2>
        <button class="btn-add" onclick="showAddProduct()"><i class="fas fa-plus"></i> إضافة منتج</button>
    </div>

    <div class="product-form" id="productForm">
        <h2>إضافة منتج جديد</h2>
        <form onsubmit="saveProduct(event)" class="product-form-content">
            <div class="form-grid">
                <div class="form-section">
                    <h3>معلومات أساسية</h3>
                    <div class="form-group">
                        <label>اسم المنتج (عربي) *</label>
                        <input type="text" name="name_ar" required>
                        <div class="error-message">هذا الحقل مطلوب</div>
                    </div>
                    <div class="form-group">
                        <label>الفئة</label>
                        <select name="category_id">
                            <option value="">اختر فئة</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo sanitize($cat['name_ar']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>الوصف</label>
                        <textarea name="description_ar" rows="4"></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>تفاصيل التسعير والمخزون</h3>
                    <div class="form-group">
                        <label>السعر *</label>
                        <input type="number" name="price" step="0.01" required>
                        <div class="error-message">هذا الحقل مطلوب</div>
                    </div>
                    <div class="form-group">
                        <label>سعر التكلفة</label>
                        <input type="number" name="cost_price" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>رصيد المخزون</label>
                        <input type="number" name="stock_quantity" value="0">
                    </div>
                    <div class="form-group">
                        <label>نسبة الخصم (%)</label>
                        <input type="number" name="discount_percent" step="0.01" value="0">
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="is_featured"> عرض مميز</label>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px;">
                <button type="button" class="btn-cancel" onclick="hideAddProduct()">إلغاء</button>
                <button type="submit" class="btn-save">حفظ المنتج</button>
            </div>
        </form>
    </div>

    <table class="products-table">
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>الفئة</th>
                <th>السعر</th>
                <th>المخزون</th>
                <th>التقييم</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            <?php foreach($products as $product): ?>
                <tr>
                    <td><?php echo sanitize($product['name_ar']); ?></td>
                    <td><?php echo $product['category_name'] ? sanitize($product['category_name']) : '-'; ?></td>
                    <td><span class="price-badge">₪<?php echo number_format($product['price'], 2); ?></span></td>
                    <td>
                        <span class="stock-badge <?php 
                            if ($product['stock_quantity'] <= 5) echo 'low';
                            elseif ($product['stock_quantity'] <= 20) echo 'medium';
                            else echo 'high';
                        ?>">
                            <?php echo $product['stock_quantity']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="rating-badge">
                            <i class="fas fa-star"></i> <?php echo number_format($product['rating_average'] ?? 0, 1); ?> 
                            (<?php echo $product['rating_count'] ?? 0; ?>)
                        </span>
                    </td>
                    <td><?php echo $product['is_active'] ? '<span style="color:green;">✓ مفعل</span>' : '<span style="color:red;">✗ معطل</span>'; ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-edit" onclick="editProduct(<?php echo $product['id']; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function showAddProduct() {
    document.getElementById('productForm').style.display = 'block';
}

function hideAddProduct() {
    document.getElementById('productForm').style.display = 'none';
}

function saveProduct(event) {
    event.preventDefault();
    
    if (!validateProductForm()) {
        Swal.fire('خطأ', 'يرجى ملء جميع الحقول المطلوبة', 'error');
        return;
    }
    
    const $submitBtn = $('.btn-save');
    $submitBtn.addClass('loading').prop('disabled', true);
    
    const formData = new FormData(event.target);
    formData.append('action', 'create_product');

    fetch('/api/products.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        $submitBtn.removeClass('loading').prop('disabled', false);
        
        if (data.success) {
            Swal.fire('نجح', 'تم إضافة المنتج بنجاح', 'success');
            hideAddProduct();
            location.reload();
        } else {
            Swal.fire('خطأ', data.error || 'حدث خطأ غير متوقع', 'error');
        }
    })
    .catch(error => {
        $submitBtn.removeClass('loading').prop('disabled', false);
        console.error('Error:', error);
        Swal.fire('خطأ', 'حدث خطأ في الإرسال', 'error');
    });
}

function validateProductForm() {
    let isValid = true;
    $('.form-group').removeClass('error');
    $('.error-message').hide();
    
    const name = $('input[name="name_ar"]').val().trim();
    const price = $('input[name="price"]').val();
    
    if (!name) {
        $('input[name="name_ar"]').closest('.form-group').addClass('error');
        isValid = false;
    }
    
    if (!price || price <= 0) {
        $('input[name="price"]').closest('.form-group').addClass('error');
        isValid = false;
    }
    
    return isValid;
}

function editProduct(productId) {
    alert('سيتم إضافة صفحة التعديل قريباً');
}

function deleteProduct(productId) {
    if (confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
        alert('سيتم حذف المنتج');
    }
}
</script>
