<?php  
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ./dashboard?signout"); exit();
}
?>

<?php include('includes/header.php') ?>
<body>

  <!-- Data Table Section -->
<section class="container mt-5">
  <div class="text-center mb-4">
    <h3>عرض المنتجات بواسطة الجدول</h3>
    <p>يمكنك التعديل والحذف بواسطة الجدول</p>
  </div>
  
  <!-- زر الإضافة -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5>إدارة المنتجات</h5>
    <a href="add" class="btn btn-success">
      <i class="bi bi-plus-circle"></i> إضافة منتج جديد
    </a>
  </div>

  <!-- جدول عرض المنتجات -->
  <table id="datatable" class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>الاي دي</th>
        <th>الاسم</th>
        <th>السعر</th>
        <th>تعديل</th>
        <th>حذف</th>
      </tr>
    </thead>
    <tbody>
      <!-- سيتم تعبئة البيانات من Database -->
    </tbody>
    <tfoot>
      <tr>
        <th>الاي دي</th>
        <th>الاسم</th>
        <th>السعر</th>
        <th>تعديل</th>
        <th>حذف</th>
      </tr>
    </tfoot>
  </table>
</section>

  <!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">تعديل المنتج</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm" enctype="multipart/form-data">
          <div class="mb-3 text-center">
            <img id="currentImage" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" alt="صورة شخصية">
          </div>
          <div class="mb-3">
            <label for="productId" class="form-label">ID</label>
            <input type="text" class="form-control" id="productId" disabled>
          </div>
          <div class="mb-3">
            <label for="productName" class="form-label">الاسم</label>
            <input type="text" class="form-control" id="productName" required>
          </div>
          <div class="mb-3">
            <label for="productPrice" class="form-label">السعر</label>
            <input type="number" class="form-control" id="productPrice" required>
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">اختار صورة جديدة</label>
            <input type="file" class="form-control" id="image" name="image">
          </div>
          <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        </form>
      </div>
    </div>
  </div>
</div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
  
<script>
$(document).ready(function () {
  const table = $('#datatable').DataTable({
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    dom: 'Bfrtip',
    buttons: [
      { extend: 'print', text: 'طباعة' },
      { extend: 'csv', text: 'CSV' },
      { extend: 'excel', text: 'Excel' },
      { extend: 'pdf', text: 'PDF' }
    ],
    ajax: {
      url: "api.php",
      type: "POST",
      data: { action: "showAll" },
      dataSrc: "data",
      error: function () {
        Swal.fire('حدث خطأ في تحميل البيانات', '', 'error');
      }
    },
    columns: [
      { data: "id" },
      { data: "name" },
      { data: "price" },
      {
        data: null,
        render: function (data, type, row) {
          return `<button class="btn btn-info text-white px-4" id="edit-btn" style="border-radius:25px" data-id="${row.id}" data-name="${row.name}" data-price="${row.price}" data-image="${row.image}"><i class="bi bi-pencil-fill"></i> تعديل</button>`;
        }
      },
      {
        data: null,
        render: function (data, type, row) {
          return `<button class="btn btn-danger text-white px-4" id="delete-btn" style="border-radius:25px" data-id="${row.id}"><i class="bi bi-trash-fill"></i> حذف</button>`;
        }
      }
    ]
  });

  // عند الضغط على زر التعديل
  $(document).on('click', '#edit-btn', function () {
    const id = $(this).data('id');
    const name = $(this).data('name');
    const price = $(this).data('price');
    const image = $(this).data('image');

    $('#productId').val(id);
    $('#productName').val(name);
    $('#productPrice').val(price);

    // Check if the image exists and show it
    if (image && image.trim() !== '') {
      // Check if the image file exists (you can implement your server-side check here if needed)
      const imagePath = 'uploads/' + image; // Assuming images are in 'uploads' folder
      $.ajax({
        url: imagePath,
        type: 'HEAD', // Check if file exists
        success: function () {
          $('#currentImage').attr('src', imagePath).show(); // Show the image
        },
        error: function () {
          $('#currentImage').hide(); // Hide image if not found
        }
      });
    } else {
      $('#currentImage').hide(); // Hide image if not provided
    }

    $('#editModal').modal('show');
  });

  // حفظ التعديلات
  $('#editForm').on('submit', function (e) {
    e.preventDefault();

    const id = $('#productId').val();
    const name = $('#productName').val();
    const price = $('#productPrice').val();
    const image = $('#image')[0].files[0];

    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', id);
    formData.append('name', name);
    formData.append('price', price);
    
    if (image) formData.append('image', image);

    $.ajax({
      url: 'api.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          Swal.fire('تم تحديث المنتج بنجاح!', '', 'success');
          $('#editModal').modal('hide');
          table.ajax.reload();
        } else {
          Swal.fire('حدث خطأ أثناء التحديث', '', 'error');
        }
      },
      error: function () {
        Swal.fire('حدث خطأ أثناء التحديث', '', 'error');
      }
    });
  });

  // عند الضغط على زر الحذف
  $(document).on('click', '#delete-btn', function () {
    const id = $(this).data('id');
    Swal.fire({
      title: 'هل أنت متأكد؟',
      text: 'لا يمكنك التراجع عن هذا!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'نعم, احذف!',
      cancelButtonText: 'إلغاء'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'api.php',
          type: 'POST',
          data: { action: 'destroyById', id },
          success: function (response) {
            if (response.success) {
              Swal.fire('تم حذف المنتج!', '', 'success');
              table.ajax.reload();
            } else {
              Swal.fire('حدث خطأ أثناء حذف المنتج', '', 'error');
            }
          },
          error: function () {
            Swal.fire('حدث خطأ أثناء حذف المنتج', '', 'error');
          }
        });
      }
    });
  });
});


</script>

</body>
</html>
