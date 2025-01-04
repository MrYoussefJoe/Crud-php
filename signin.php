<!DOCTYPE html>
<html lang="ar" dir="rtl">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" href="imgs/cis.ico">
      <title>صفحة تسجيل الدخول</title>
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
      <!-- jQuery -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Cairo:wght@200..1000&family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
      <style>
         body {
         background-image: url('https://i.postimg.cc/W11cDBzH/desk.jpg');
         background-size: cover;
         background-repeat: no-repeat;
         display: flex;
         justify-content: center;
         align-items: center;
         min-height: 100vh;
         font-family: "Amiri", serif;
         font-weight: 400;
         }
         .login-box {
         background: rgba(255, 255, 255, 0.2);
         border-radius: 15px;
         padding: 30px;
         width: 100%;
         max-width: 400px;
         }
         .login-box .form-control {
         margin-bottom: 15px;
         }
         .login-box button {
         width: 100%;
         font-size: 16px;
         }
         .login-box .link {
         text-align: center;
         margin-top: 20px;
         }
         .login-box .link a {
         text-decoration: none;
         font-weight: bold;
         color: #007bff;
         }
         .login-box .link a:hover {
         text-decoration: underline;
         }
      </style>
   </head>
   <body>
      <div class="login-box shadow">
         <p style="background-color:black;text-align:center;color:white;padding-top:10px;padding-bottom:10px"> مشروع الأعمال الالكترونية بواسطة الطالب <span style="color:green">يوسف سامي</span></p>
         <div class=" text-center">
            <img src="imgs/cis.png" alt="" width='30%'>
         </div>
         <form id="loginForm">
            <h3 class="text-center mb-4">تسجيل الدخول</h3>
            <!-- حقل البريد الإلكتروني -->
            <div class="form-floating mb-3">
               <input type="email" id="email" name="email" class="form-control" placeholder="البريد الإلكتروني">
               <label for="email">البريد الإلكتروني</label>
            </div>
            <!-- حقل كلمة المرور -->
            <div class="form-floating mb-3">
               <input type="password" id="password" name="password" class="form-control" placeholder="كلمة المرور">
               <label for="password">كلمة المرور</label>
            </div>
            <!-- زر تسجيل الدخول -->
            <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
            <!-- رابط إنشاء حساب جديد -->
            <div class="link">
               <p>لا تمتلك حسابًا؟ <a href="register.php">إنشاء حساب جديد</a></p>
            </div>
         </form>
      </div>
      <!-- Script AJAX -->
      <script>
         $(document).ready(function () {
  $('#loginForm').submit(function (event) {
    event.preventDefault();

    // الحصول على البيانات من الحقول
    var formData = {
      action: 'signin',
      email: $('#email').val(),
      password: $('#password').val()
    };

    // إرسال البيانات باستخدام AJAX
    $.ajax({
      url: 'api.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          window.location.href = 'table.php';  // أو أي صفحة تريد توجيه المستخدم إليها بعد تسجيل الدخول
        } else {
          alert(response.message);  // في حالة الفشل، عرض الرسالة المرسلة من الـ API
        }
      },
      error: function () {
        alert("حدث خطأ أثناء تسجيل الدخول. حاول مرة أخرى.");
      }
    });
  });
});

      </script>
      <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   </body>
</html>
