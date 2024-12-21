<?php
// Kết nối cơ sở dữ liệu thông qua file ketnoi.php
include '../ketnoi/ketnoi.php';

// Khởi động phiên làm việc của session
session_start();

// Lấy ID quản trị viên từ session
$admin_id = $_SESSION['admin_id'];

// Kiểm tra nếu không có ID quản trị viên trong session
if (!isset($admin_id)) {
   // Chuyển hướng đến trang đăng nhập nếu không có quyền truy cập
   header('location:admin_login.php');
}

// Kiểm tra xem có tham số delete trong URL không
// if (isset($_GET['delete'])) {
//    // Lấy ID cần xóa từ tham số GET
//    // $delete_id = $_GET['delete'];

//    // // Chuẩn bị câu lệnh SQL để xóa admin
//    // $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
//    // // Thực thi câu lệnh SQL với giá trị ID
//    // $delete_admin->execute([$delete_id]);

//    // Chuyển hướng về trang danh sách account sau khi xóa thành công
//    header('location:admin_account.php');
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tài khoản admin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../styles_admin/admin_giaodien.css">
   <link rel="stylesheet" href="../styles_admin/table.css">



</head>

<body>

   <?php include '../ketnoi/admin_header.php' ?>

   <!-- admins accounts section starts  -->

   <section class="accounts">

      <h1 class="heading">Admin</h1>



      </div>
      <div>
         <table class="table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Tên</th>
                  <!-- <th>Thao tác</th> -->
               </tr>
            </thead>
            <tbody>
               <?php
               // Chuẩn bị truy vấn SQL để lấy tất cả thông tin từ bảng `admin`
               $select_account = $conn->prepare("SELECT * FROM `admin`");
               // Thực thi truy vấn
               $select_account->execute();
               // Kiểm tra xem có bất kỳ bản ghi nào được trả về không
               if ($select_account->rowCount() > 0) {
                  // Nếu có, lặp qua từng bản ghi
                  while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <!-- Hiển thị ID của admin -->
                        <td><span><?= $fetch_accounts['id']; ?></span></td>
                        <!-- Hiển thị tên của admin -->
                        <td><span><?= $fetch_accounts['name']; ?></span></td>
                        <!-- Ghi chú: Đoạn mã comment dưới đây đang bị vô hiệu hóa do mục đích minh họa. -->
                        <!-- <td>
                    <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Xóa tài khoản này?');">
                        <button><i class="fa-solid fa-trash"></i></button>
                    </a>
                </td> -->
                     </tr>
               <?php
                  }
               } else {
                  // Nếu không có bản ghi nào, hiển thị thông báo
                  echo '<p class="empty">Chưa có admin nào</p>';
               }
               ?>
            </tbody>

         </table>
      </div>

   </section>

   <!-- admins accounts section ends -->

   <!-- custom js file link  -->
   <script src="../javascript/admin_login.js"></script>

</body>

</html>