<?php
session_start();
include('includes/header.php');
?>
<div class="container mt-5">
  <!-- Header -->
  <header class="text-center mb-4">
    <h1 class="display-5">عن الطالب</h1>
  </header>

  <!-- Personal Info -->
  <div class="card mb-4 shadow">
    <div class="row g-0">
      <div class="col-md-4 text-center pt-2">
        <img src="imgs/BEBO3979.jpg" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" alt="صورة شخصية">
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h3 class="card-title">يوسف  جو </h3>
          <p class="card-text">
            <strong>التخصص:</strong> نظم ومعلومات<br>
            <strong>المعهد:</strong> القاهره الجديده <br>
            <strong>البريد الإلكتروني:</strong> youssefsamy10231@gmail.com<br>
            <strong>المهارات:</strong> <br>
            <ul>
              <li>برمجة الويب باستخدام PHP, HTML, CSS, JavaScript</li>
              <li>قواعد بيانات MySQL</li>
              <li>تطوير تطبيقات باستخدام JavaScript و jQuery</li>
              <li>استخدام Bootstrap لتصميم واجهات المستخدم</li>
              <li>تطوير مشاريع باستخدام PHP و AJAX</li>
            </ul>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- About Me -->
  <section class="mb-4">
    <h2 class="text-primary">نبذة شخصية</h2>
    <p>أسعى إلى تطوير نفسي في مجال البرمجة والعمل على مشاريع تكنولوجية مبتكرة، خاصة في مجالات الذكاء الاصطناعي وتطوير الويب. أستمتع بتعلم تقنيات جديدة وأحب العمل على مشاريع تجمع بين التصميم والتطوير.</p>
  </section>

  <!-- Graduation Project -->
  <section class="mb-4">
    <h2 class="text-primary">مشروع لمادة الأعمال الإلكترونية</h2>
    <p><strong>اسم المشروع:</strong> بروجيكت CRUD بسيط</p>

    <ul class="list-group mb-3">
      <li class="list-group-item">تسجيل دخول</li>
      <li class="list-group-item">تسجيل حساب جديد</li>
      <li class="list-group-item">إضافة منتج</li>
      <li class="list-group-item">تعديل المنتج</li>
      <li class="list-group-item">حذف منتج</li>
      <li class="list-group-item">عرض المنتجات</li>
      <li class="list-group-item">عرض المنتج بالمعرف</li>
      <li class="list-group-item">الايميل: admin@admin.com</li>
      <li class="list-group-item">كلمه السر: 123456</li>
    </ul>

    <p><strong>الأدوات المستخدمة:</strong> HTML, CSS, Bootstrap, jQuery, AJAX, PHP, MySQL</p>

    <p>المشروع: 
      <a class="btn btn-link" href="./table" target="_blank">اضغط هنا لزيارة المشروع</a>
    </p>
  </section>

  <!-- Contact Links -->
  <footer class="text-center">
    <h3 class="text-primary">روابط التواصل</h3>
    <a href="https://www.linkedin.com/in/youssef-samy-9bbab92b9/" class="btn btn-outline-primary mx-2" target="_blank">LinkedIn</a>
    <a href="https://github.com/MrYoussefJoe" class="btn btn-outline-dark mx-2" target="_blank">GitHub</a>
    <a href="mailto:youssefsamy10231@gmail.com" class="btn btn-outline-danger mx-2">Email</a>
    <a href="https://wa.me/+201080745492" class="btn btn-outline-success mx-2">Whatsapp</a>
    <a href="tel:+201080745492" class="btn btn-outline-info mx-2">Phone</a>
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- تضمين مكتبة SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_GET['signout'])) {?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'لازم تسجل الدخول',
      text: 'الايميل والباسورد موجودين في الصفحة.',
      confirmButtonText: 'حاول مرة أخرى'
    });
  </script>
<?php } ?>

</body>
</html>
