<?php

include 'ketnoi/ketnoi.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user/user_login.php');
   exit(); // Ensure no further code is executed if not logged in
};

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đặt hàng</title>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="styles_using/font.css">
   <link rel="stylesheet" href="styles_using/main.css">
   <link rel="stylesheet" href="styles_using/table_order.css">
   <link rel="stylesheet" href="styles_using/home_giaodien.css">
   <link rel="stylesheet" href="styles_using/order.css">
   <link rel="website icon" type="png" href="images/logo2.png.jpeg">

   <script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>
</head>

<body>

   <!-- Bắt đầu bằng việc bao gồm header người dùng -->
   <?php include 'user/user_header.php'; ?>


   <?php
   // Kiểm tra xem người dùng đã đăng nhập hay chưa
   if ($user_id == '') {
      // Nếu chưa đăng nhập, hiển thị thông báo yêu cầu đăng nhập
      echo '<p class="empty">Hãy đăng nhập để xem được nhé</p>';
   } else {
      // Nếu đã đăng nhập, tiến hành truy vấn dữ liệu đơn hàng
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id =?");
      $select_orders->execute([$user_id]); // Sử dụng tham số hóa động

      if ($select_orders->rowCount() > 0) {
         // Nếu có đơn hàng, hiển thị danh sách đơn hàng
         while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
            // Tính phí vận chuyển dựa trên tổng giá tiền
            $total_price = $fetch_orders['total_price'];
            $shipping_cost = ($total_price > 100000) ? 0 : 30000;
            $total_price_with_shipping = $total_price + $shipping_cost;
   ?>
            <table class="table">
               <thead>
                  <tr>
                     <th width="100px">Ngày đặt hàng</th>
                     <th width="100px">Tên</th>
                     <th width="100px">Email</th>
                     <th width="100px">Số điện thoại</th>
                     <th width="100px">Địa chỉ</th>
                     <th width="180px">Sản phẩm</th>
                     <th width="100px">Giá tiền</th>
                     <th width="100px">Phí vận chuyển</th>
                     <th width="100px">Tổng giá tiền</th>
                     <th width="120px">Phương thức thanh toán</th>
                     <th width="100px">Trạng thái</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <!-- Hiển thị thời gian đặt hàng -->
                     <td><?= htmlspecialchars($fetch_orders['placed_on']); ?></td>

                     <!-- Hiển thị tên khách hàng -->
                     <td><?= htmlspecialchars($fetch_orders['name']); ?></td>

                     <!-- Hiển thị email khách hàng -->
                     <td><?= htmlspecialchars($fetch_orders['email']); ?></td>

                     <!-- Hiển thị số điện thoại khách hàng -->
                     <td><?= htmlspecialchars($fetch_orders['number']); ?></td>

                     <!-- Hiển thị địa chỉ khách hàng -->
                     <td><?= htmlspecialchars($fetch_orders['address']); ?></td>

                     <!-- Hiển thị tổng số sản phẩm trong đơn hàng -->
                     <td><?= nl2br(htmlspecialchars(str_replace(', ', "\n", $fetch_orders['total_products']))); ?></td>

                     <!-- Hiển thị tổng tiền của đơn hàng -->
                     <td><?= number_format($total_price, 0, ',', '.'); ?>đ</td>

                     <!-- Hiển thị phí vận chuyển -->
                     <td><?= number_format($shipping_cost, 0, ',', '.'); ?>đ</td>

                     <!-- Hiển thị tổng tiền bao gồm phí vận chuyển -->
                     <td><?= number_format($total_price_with_shipping, 0, ',', '.'); ?>đ</td>

                     <!-- Hiển thị phương thức thanh toán -->
                     <td><?= htmlspecialchars($fetch_orders['method']); ?></td>

                     <!-- Hiển thị trạng thái thanh toán với màu sắc tương ứng -->
                     <td>
                        <span style="color:<?php
                                             // Nếu trạng thái thanh toán là 'Đã đặt hàng', hiển thị màu đỏ
                                             if ($fetch_orders['payment_status'] == 'Đã đặt hàng') {
                                                echo 'red';
                                             } else { // Trong trường hợp khác, hiển thị màu xanh
                                                echo 'green';
                                             };
                                             ?>"><?= htmlspecialchars($fetch_orders['payment_status']); ?></span>
                     </td>
                  </tr>
               </tbody>

            </table>
   <?php
         }
      } else {
         // Nếu không có đơn hàng, hiển thị thông báo
         echo ' <div class="empty_cart">
            <i class="fas fa-shopping-bag"></i>
            <h1>
                <a href="home.php"><i class="fas fa-arrow-left"></i></a>Bạn chưa đặt đơn hàng nào!
            </h1>
        </div>';
      }
   }
   ?>

</body>

</html>
