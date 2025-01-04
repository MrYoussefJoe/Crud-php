<!DOCTYPE html>
<html lang="ar" dir="rtl">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" href="imgs/cis.ico">
      <title>صفحة التسجيل</title>
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
      <!-- jQuery -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
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
         font-style: normal;
         }
         .register-box {
         background: rgba(255, 255, 255, 0.2);
         border-radius: 15px;
         padding: 30px;
         width: 100%;
         max-width: 400px;
         }
         .register-box .form-control {
         margin-bottom: 15px;
         }
         .register-box button {
         width: 100%;
         font-size: 16px;
         }
         .register-box .link {
         text-align: center;
         margin-top: 20px;
         }
         .register-box .link a {
         text-decoration: none;
         font-weight: bold;
         color: #007bff;
         }
         .register-box .link a:hover {
         text-decoration: underline;
         }
         #alertMessage {
         display: none;
         }
      </style>
   </head>
   <body>
      <div class="register-box shadow">
      <p style="background-color:black;text-align:center;color:white;padding-top:10px;padding-bottom:10px"> مشروع الأعمال الالكترونية بواسطه الطالب <span style="color:green">يوسف سامي</span></p>
         <div class=" text-center">
            <img src="imgs/cis.png" alt="" width='30%'>
         </div>
         <form id="registerForm">
            <h3 class="text-center mb-4">تسجيل جديد</h3>
            <div id="alertMessage" class="alert" role="alert"></div>
            <!-- حقل اسم المستخدم -->
            <div class="form-floating mb-3">
               <input type="text" id="username" name="username" class="form-control" placeholder="اسم المستخدم" required>
               <label for="username">اسم المستخدم</label>
            </div>
            <!-- حقل البريد الإلكتروني -->
            <div class="form-floating mb-3">
               <input type="email" id="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
               <label for="email">البريد الإلكتروني</label>
            </div>
            <!-- حقل الاسم الكامل -->
            <div class="form-floating mb-3">
               <input type="text" id="fullName" name="fullName" class="form-control" placeholder="الاسم الكامل" required>
               <label for="fullName">الاسم الكامل</label>
            </div>
            <!-- حقل كلمة المرور -->
            <div class="form-floating mb-3">
               <input type="password" id="password" name="password" class="form-control" placeholder="كلمة المرور" required>
               <label for="password">كلمة المرور</label>
            </div>
            <!-- حقل تأكيد كلمة المرور -->
            <div class="form-floating mb-3">
               <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="تأكيد كلمة المرور" required>
               <label for="confirmPassword">تأكيد كلمة المرور</label>
            </div>
            <!-- زر التسجيل -->
            <button type="submit" class="btn btn-primary">تسجيل</button>
            <!-- رابط تسجيل الدخول -->
            <div class="link">
               <p>لديك حساب بالفعل؟ <a href="signin.php">تسجيل الدخول</a></p>
            </div>
         </form>
      </div>
      <!-- Script AJAX -->
      <script>
    $(document).ready(function () {
        $('#registerForm').submit(function (event) {
            event.preventDefault();

            // الحصول على البيانات من الحقول
            var formData = {
                action: 'register',
                username: $('#username').val(),
                email: $('#email').val(),
                fullName: $('#fullName').val(),
                password: $('#password').val(),
                confirmPassword: $('#confirmPassword').val()
            };

            // التحقق من تطابق كلمة المرور وتأكيد كلمة المرور
            if (formData.password !== formData.confirmPassword) {
                showMessage('كلمتا المرور غير متطابقتين.', 'danger');
                return;
            }

            // إرسال البيانات باستخدام AJAX
            $.ajax({
                url: 'api.php', // URL للـ API الخاص بك
                type: 'POST',
                data: formData, // البيانات المرسلة
                dataType: 'json', // نوع البيانات المستقبلة من السيرفر
                beforeSend: function () {
                    // تعطيل الزر أثناء الإرسال
                    $('button[type="submit"]').prop('disabled', true).text('جاري التسجيل...');
                },
                success: function (response) {
                    if (response.success) {
                        showMessage('تم التسجيل بنجاح!', 'success');
                        setTimeout(function () {
                            window.location.href = response.data.redirect; // إعادة التوجيه بعد التسجيل
                        }, 2000);
                    } else {
                        showMessage(response.message, 'danger'); // عرض رسالة الخطأ
                    }
                },
                error: function () {
                    showMessage('حدث خطأ أثناء التسجيل. حاول مرة أخرى.', 'danger');
                },
                complete: function () {
                    // إعادة تفعيل الزر بعد إتمام العملية
                    $('button[type="submit"]').prop('disabled', false).text('تسجيل');
                }
            });
        });

        // دالة لعرض الرسائل
        function showMessage(message, type) {
            var alertBox = $('#alertMessage');
            alertBox
                .removeClass('d-none alert-success alert-danger')
                .addClass('alert-' + type)
                .text(message)
                .fadeIn();
            setTimeout(function () {
                alertBox.fadeOut();
            }, 3000);
        }
    });
</script>


      <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   </body>
</html>
