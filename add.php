<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ./signin"); exit();
}
include('includes/header.php');
?>

<div class="container mt-5">
    <h3 class="text-center mb-4">إضافة منتج جديد</h3>
    <form id="productForm" method="post" enctype="multipart/form-data">
      <!-- اسم المنتج -->
      <div class="mb-3">
        <label for="productName" class="form-label">اسم المنتج</label>
        <input type="text" name="name" class="form-control" id="productName" required>
        <div class="invalid-feedback">من فضلك أدخل اسم المنتج.</div>
      </div>
      
      <!-- السعر -->
      <div class="mb-3">
        <label for="productPrice" class="form-label">السعر</label>
        <input type="number" name="price" class="form-control" id="productPrice" required>
        <div class="invalid-feedback">من فضلك أدخل السعر.</div>
      </div>
      
      <!-- الصورة -->
      <div class="mb-3">
        <label for="productImage" class="form-label">صورة المنتج</label>
        <input type="file" name="image" class="form-control" id="productImage" accept="image/*" required>
        <div class="invalid-feedback">من فضلك اختر صورة للمنتج.</div>
      </div>
      
      <!-- الوصف -->
      <div class="mb-3">
        <label for="productDescription" class="form-label">الوصف</label>
        <textarea name="description" class="form-control" id="productDescription" rows="4"></textarea>
        <div class="invalid-feedback">من فضلك أدخل وصف المنتج.</div>
      </div>
      
      <!-- الكمية -->
      <div class="mb-3">
        <label for="productQuantity" class="form-label">الكمية</label>
        <input type="number" name="quantity" class="form-control" id="productQuantity" required>
        <div class="invalid-feedback">من فضلك أدخل كمية المنتج.</div>
      </div>
      
      <!-- التقييم -->
      <div class="mb-3">
        <label for="productRating" class="form-label">التقييم</label>
        <select name="rating" class="form-select" id="productRating" required>
          <option value="" selected disabled>اختر التقييم</option>
          <option value="1">1 - ضعيف</option>
          <option value="2">2 - مقبول</option>
          <option value="3">3 - جيد</option>
          <option value="4">4 - جيد جداً</option>
          <option value="5">5 - ممتاز</option>
        </select>
        <div class="invalid-feedback">من فضلك اختر تقييم المنتج.</div>
      </div>
      
      <!-- زر الإرسال -->
      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">إضافة المنتج</button>
        <a href="table" class="btn btn-secondary">العودة</a>
      </div>
    </form>
</div>

<!-- تضمين مكتبة SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- تضمين Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // التحقق من صحة المدخلات
        $('#productForm').on('submit', function (e) {
            e.preventDefault();
            let form = $(this)[0];

            // التحقق من صلاحية النموذج
            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // إنشاء البيانات لإرسالها
            let formData = new FormData(this);
            formData.append('action', 'add');

            // إرسال البيانات باستخدام AJAX
            $.ajax({
                url: 'api.php', 
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // التحقق من نجاح العملية
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم إضافة المنتج بنجاح!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            $('#productForm')[0].reset(); // إعادة تعيين النموذج
                            $('#productForm').removeClass('was-validated');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'حدث خطأ أثناء إضافة المنتج',
                            text: response.message,
                            confirmButtonText: 'حاول مرة أخرى'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'حدث خطأ أثناء إرسال البيانات',
                        text: 'يرجى المحاولة مرة أخرى.',
                        confirmButtonText: 'حاول مرة أخرى'
                    });
                }
            });
        });
    });
</script>
