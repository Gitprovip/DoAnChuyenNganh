<?php

// Include file kết nối cơ sở dữ liệu
include '../ketnoi/ketnoi.php';

// Khởi động phiên session
session_start();

// Lấy ID quản trị viên từ session
$admin_id = $_SESSION['admin_id'];

// Nếu ID quản trị viên không có, chuyển hướng về trang đăng nhập
if (!isset($admin_id)) {
   header('location:admin_login.php');
};

// Kiểm tra xem có yêu cầu xóa người dùng hay không
if (isset($_GET['delete'])) {
   // Lấy ID người dùng cần xóa từ query string
   $delete_id = $_GET['delete'];

   // Xóa các dòng liên quan trong bảng orders
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id =?");
   $delete_orders->execute([$delete_id]);

   // Xóa các dòng liên quan trong bảng cart
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id =?");
   $delete_cart->execute([$delete_id]);

   // Xóa các dòng tin nhắn trong bảng messages
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id =?");
   $delete_messages->execute([$delete_id]);

   // Xóa các dòng liên quan trong bảng review_table
   $delete_reviews = $conn->prepare("DELETE FROM `review_table` WHERE user_id =?");
   $delete_reviews->execute([$delete_id]);

   // Cuối cùng, xóa người dùng khỏi bảng users
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id =?");
   $delete_users->execute([$delete_id]);

   

   // Chuyển hướng người dùng về trang quản lý tài khoản sau khi xóa
   header('location:users_account.php');
};
?>



<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tài khoản người dùng</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../styles_admin/admin_giaodien.css">
   <link rel="stylesheet" href="../styles_admin/table.css">

</head>

<body>

   <?php include '../ketnoi/admin_header.php' ?>

   <!-- user accounts section starts  -->

   <section class="accounts">

      <h1 class="heading">Khách hàng</h1>

      </div>

      </div>
      <div>
         <table class="table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Tên</th>
                  <th>Email</th>
                  <th>Địa chỉ</th>
                  <th>Thao tác</th>
               </tr>
            </thead>
            <!-- Bắt đầu phần hiển thị danh sách tài khoản -->
            <tbody>
               <?php
               // Chuẩn bị câu lệnh SQL để lấy tất cả tài khoản từ bảng `users`
               $select_account = $conn->prepare("SELECT * FROM `users`");

               // Thực thi câu lệnh SQL
               $select_account->execute();

               // Kiểm tra xem có tài khoản nào được trả về từ cơ sở dữ liệu không
               if ($select_account->rowCount() > 0) {
                  // Duyệt qua từng tài khoản được trả về
                  while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <!-- Hiển thị ID của tài khoản -->
                        <td><span><?= $fetch_accounts['id']; ?></span></td>

                        <!-- Hiển thị tên của tài khoản -->
                        <td><span><?= $fetch_accounts['name']; ?></span></td>

                        <!-- Hiển thị email của tài khoản -->
                        <td><span><?= $fetch_accounts['email']; ?></span></td>

                        <!-- Hiển thị địa chỉ của tài khoản -->
                        <td><span><?= $fetch_accounts['address']; ?></span></td>

                        <!-- Hiển thị nút xóa, sử dụng icon thùng rác để biểu thị hành động xóa, hiển thị hộp thoại xác nhận trước khi thực hiện -->
                        <td>
                           <a href="users_account.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Xóa tài khoản này?');">
                              <button><i class="fa-solid fa-trash"></i></button>
                           </a>
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  // Hiển thị thông báo nếu không có tài khoản nào được thêm
                  echo '<p class="empty">Chưa có tài khoản nào</p>';
               }
               ?>
            </tbody>
            <!-- Kết thúc phần hiển thị danh sách tài khoản -->

         </table>
      </div>

   </section>

   <!-- user accounts section ends -->

   <!-- custom js file link  -->
   <script src="../javascript/admin_login.js"></script>

</body>

</html>