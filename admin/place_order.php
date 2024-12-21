<?php

// Include file kết nối cơ sở dữ liệu
include '../ketnoi/ketnoi.php';

// Khởi động phiên session
session_start();

// Lấy ID của admin từ session
$admin_id = $_SESSION['admin_id'];

// Kiểm tra xem ID admin có tồn tại không
if (!isset($admin_id)) {
   // Nếu không, chuyển hướng đến trang đăng nhập
   header('location:admin_login.php');
   exit(); // Ensure no further code is executed if not logged in
}

// Kiểm tra xem có thao tác cập nhật trạng thái thanh toán mới không
if (isset($_POST['update_payment'])) {

   // Lấy ID đơn hàng và trạng thái thanh toán từ form
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];

   // Chuẩn bị câu lệnh SQL để cập nhật trạng thái thanh toán của đơn hàng
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status =? WHERE id =?");
   // Thực thi câu lệnh với tham số đã định sẵn
   $update_status->execute([$payment_status, $order_id]);

   // Thêm thông báo thành công
   $success_msg[] = 'Đã cập nhật';
}

// Kiểm tra xem có thao tác xóa đơn hàng mới không
if (isset($_GET['delete'])) {
   // Lấy ID của đơn hàng cần xóa từ query string
   $delete_id = $_GET['delete'];

   // Chuẩn bị câu lệnh SQL để xóa đơn hàng
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id =?");
   // Thực thi câu lệnh với tham số đã định sẵn
   $delete_order->execute([$delete_id]);

   // Chuyển hướng trở lại trang quản lý đơn hàng
   header('location:place_order.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đơn hàng</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../styles_admin/admin_order.css">
   <link rel="stylesheet" href="../styles_admin/table.css">


</head>

<body>

   <?php include '../ketnoi/admin_header.php' ?>
   <?php include '../ketnoi/thongbao.php'; ?>
   <!-- placed orders section starts  -->

   <section class="placed-orders">

      <h1 class="heading">Danh mục mua hàng</h1>

      <div>
         <table class="table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th width="100px">Ngày đặt hàng</th>
                  <th>Tên</th>
                  <th>Email</th>
                  <th>Số điện thoại</th>
                  <th>Địa chỉ</th>
                  <th width="240px;">Sản phẩm</th>
                  <th>Giá tiền</th>
                  <th>Phí Vận chuyển</th>
                  <th>Tổng giá tiền</th>
                  <th>Phương thức thanh toán</th>
                  <th>Thao tác</th>
               </tr>
            </thead>
            <tbody>
               <?php
               // Chuẩn bị truy vấn SQL để lấy tất cả đơn hàng từ bảng `orders`
               $select_orders = $conn->prepare("SELECT * FROM `orders`");
               // Thực thi truy vấn
               $select_orders->execute();
               // Kiểm tra xem có bất kỳ bản ghi nào được trả về không
               if ($select_orders->rowCount() > 0) {
                  // Nếu có, lặp qua từng bản ghi
                  while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                     // Tính phí vận chuyển dựa trên tổng giá tiền
                     $total_price = $fetch_orders['total_price'];
                     $shipping_cost = ($total_price > 100000) ? 0 : 30000;
                     $total_price_with_shipping = $total_price + $shipping_cost;
               ?>
                     <tr>
                        <!-- Hiển thị ID người dùng đặt hàng -->
                        <td><?= htmlspecialchars($fetch_orders['user_id']); ?></td>

                        <!-- Hiển thị thời gian đặt hàng -->
                        <td><?= htmlspecialchars($fetch_orders['placed_on']); ?></td>

                        <!-- Hiển thị tên người dùng -->
                        <td><?= htmlspecialchars($fetch_orders['name']); ?></td>

                        <!-- Hiển thị email người dùng -->
                        <td><?= htmlspecialchars($fetch_orders['email']); ?></td>

                        <!-- Hiển thị số điện thoại người dùng -->
                        <td><?= htmlspecialchars($fetch_orders['number']); ?></td>

                        <!-- Hiển thị địa chỉ người dùng -->
                        <td><?= htmlspecialchars($fetch_orders['address']); ?></td>

                        <!-- Hiển thị tổng số lượng sản phẩm trong đơn hàng -->
                        <td><?= nl2br(htmlspecialchars(str_replace(', ', "\n", $fetch_orders['total_products']))); ?></td>

                        <!-- Hiển thị tổng giá trị đơn hàng -->
                        <td><?= number_format($total_price, 0, ',', '.'); ?>đ</td>

                        <!-- Hiển thị phí vận chuyển -->
                        <td><?= number_format($shipping_cost, 0, ',', '.'); ?>đ</td>

                        <!-- Hiển thị tổng giá trị đơn hàng bao gồm phí vận chuyển -->
                        <td><?= number_format($total_price_with_shipping, 0, ',', '.'); ?>đ</td>

                        <!-- Hiển thị phương thức thanh toán -->
                        <td><?= htmlspecialchars($fetch_orders['method']); ?></td>
                        <!-- Phần cập nhật và xóa đơn hàng -->

                        <td>
                           <form action="" method="POST">
                              <!-- Giữ ID đơn hàng ẩn trong form để sử dụng khi cập nhật -->
                              <input type="hidden" name="order_id" value="<?= htmlspecialchars($fetch_orders['id']); ?>">
                              <!-- Dropdown để chọn trạng thái thanh toán -->
                              <select name="payment_status" class="drop-down">
                                 <!-- Trạng thái hiện tại của đơn hàng -->
                                 <option><?= htmlspecialchars($fetch_orders['payment_status']); ?></option>
                                 <!-- Cài đặt các tùy chọn trạng thái thanh toán -->
                                 <option value="Đang xử lý">Đang xử lý</option>
                                 <option value="Đang giao hàng">Đang giao hàng</option>
                                 <option value="Hoàn tất">Hoàn tất</option>
                              </select>
                              <!-- Nút nhấn để cập nhật trạng thái thanh toán -->
                              <div class="flex-btn">
                                 <input type="submit" value="Cập nhật" class="btn" name="update_payment">
                                 <!-- Link để xóa đơn hàng, yêu cầu xác nhận trước khi thực hiện -->
                                 <a href="place_order.php?delete=<?= htmlspecialchars($fetch_orders['id']); ?>" class="delete-btn" onclick="return confirm('Xóa order này?');">Xóa</a>
                              </div>
                           </form>
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  // Nếu không có đơn hàng nào, hiển thị thông báo
                  echo '<p class="empty">Chưa có ai mua sản phẩm</p>';
               }
               ?>
            </tbody>

         </table>
      </div>

   </section>

   <!-- placed orders section ends -->

   <!-- custom js file link  -->
   <script src="../javascript/admin_login.js"></script>

</body>

</html>