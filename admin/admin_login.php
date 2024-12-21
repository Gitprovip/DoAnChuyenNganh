<?php
// Kết nối cơ sở dữ liệu thông qua file ketnoi.php
include '../ketnoi/ketnoi.php';

// Khởi động phiên làm việc của session
session_start();

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

   // Chuẩn bị câu lệnh SQL để chọn admin dựa trên tên và mật khẩu
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   // Thực thi câu lệnh SQL với giá trị tên và mật khẩu
   $select_admin->execute([$name, $pass]);

   // Kiểm tra nếu có ít nhất một admin phù hợp
   if ($select_admin->rowCount() > 0) {
      // Lấy thông tin của admin phù hợp
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      // Lưu ID admin vào session
      $_SESSION['admin_id'] = $fetch_admin_id['id'];
      // Chuyển hướng đến dashboard nếu đăng nhập thành công
      header('location:dashboard.php');
   } else {
      // Thêm thông báo lỗi nếu tên đăng nhập hoặc mật khẩu không đúng
      $warning_msg[] = 'Sai tên đăng nhập hoặc mật khẩu';
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng nhập Admin</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
   <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
   <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../styles_admin/login.css">

</head>

<body>
   <?php include '../ketnoi/thongbao.php'; ?>



   <div class="container">
      <div class="info">
         <h1>Đăng nhập Admin</h1>
      </div>
   </div>
   <div class="form">
      <div class="thumbnail"></div>
      <form class="login-form" method="post">
         <input type="text" placeholder="Tên tài khoản" name="name" />
         <input type="password" placeholder="Mật khẩu" name="pass" />
         <input type="submit" name="submit" value="Đăng nhập" />

      </form>
      <button onclick="redirect()">Về Trang User</button>

<script>
function redirect() {
    window.location.href = '/doannam4/home.php';
}
</script>

   </div>
   <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

   <!-- admin login form section ends -->

</body>

</html>