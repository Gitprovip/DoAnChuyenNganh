<?php
// Kết nối cơ sở dữ liệu thông qua file ketnoi.php
include '../ketnoi/ketnoi.php';

// Khởi động phiên làm việc của session
session_start();

// Lấy ID quản trị viên từ session


// Kiểm tra nếu không có ID quản trị viên trong session
if (!isset($admin_id)) {
   // Chuyển hướng đến trang đăng nhập nếu không có quyền truy cập

};

// Kiểm tra nếu nút submit được nhấn
if (isset($_POST['submit'])) {
   // Lấy tên đăng nhập từ form
   $name = $_POST['name'];
   // Xử lý sanitization cho tên đăng nhập
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   // Lấy mật khẩu từ form
   $pass = sha1($_POST['pass']);
   // Xử lý sanitization cho mật khẩu
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Lấy xác nhận mật khẩu từ form
   $cpass = sha1($_POST['cpass']);
   // Xử lý sanitization cho xác nhận mật khẩu
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Chuẩn bị câu lệnh SQL để chọn admin dựa trên tên
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   // Thực thi câu lệnh SQL với giá trị tên
   $select_admin->execute([$name]);

   // Kiểm tra nếu có admin tồn tại với tên đó
   if ($select_admin->rowCount() > 0) {
      // Thêm thông báo lỗi nếu tên đăng nhập đã tồn tại
      $warning_msg[] = 'Tài khoản đã tồn tại!';
   } else {
      // Kiểm tra nếu mật khẩu và xác nhận mật khẩu khớp nhau
      if ($pass != $cpass) {
         // Thêm thông báo lỗi nếu mật khẩu không khớp
         $warning_msg[] = 'Mật khẩu không trùng khớp';
      } else {
         // Chuẩn bị câu lệnh SQL để chèn mới admin
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         // Thực thi câu lệnh SQL với giá trị tên và mật khẩu
         $insert_admin->execute([$name, $cpass]);
         // Thêm thông báo thành công nếu đăng ký mới thành công
         $success_msg[] = 'Đăng ký thành công';
         header('location:admin_login.php');
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/dashboard_style.css">

</head>

<body>


   <!-- register admin section starts  -->

   <section class="form-container">

      <form action="" method="POST">
         <h3>register new</h3>
         <input type="text" name="name" maxlength="20" required placeholder="enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" maxlength="20" required placeholder="confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="register now" name="submit" class="btn">
      </form>

   </section>

   <!-- register admin section ends -->

   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

</body>

</html>