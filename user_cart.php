<?php

// Bao gồm file kết nối database
include 'ketnoi/ketnoi.php';

// Khởi động phiên session
session_start();

// Kiểm tra xem session 'user_id' có tồn tại không
if (isset($_SESSION['user_id'])) {
   // Nếu có, gán giá trị cho biến $user_id
   $user_id = $_SESSION['user_id'];
} else {
   // Nếu không, gán giá trị rỗng cho $user_id và chuyển hướng người dùng về trang chủ
   $user_id = '';
   header('location:../doannam4/user/user_login.php');
};

// Kiểm tra xem có thao tác xóa mục trong giỏ hàng không
if (isset($_POST['delete'])) {
   // Lấy id mục cần xóa từ POST
   $cart_id = $_POST['cart_id'];
   // Chuẩn bị câu lệnh SQL để xóa mục khỏi giỏ hàng
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id =?");
   // Thực thi câu lệnh SQL
   $delete_cart_item->execute([$cart_id]);
   // Thêm thông báo thành công vào mảng message
   $success_msg[] = 'Đã xóa sản phẩm';
}

// Kiểm tra xem có thao tác xóa tất cả mục trong giỏ hàng không
if (isset($_POST['delete_all'])) {
   // Chuẩn bị câu lệnh SQL để xóa tất cả mục của người dùng khỏi giỏ hàng
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id =?");
   // Thực thi câu lệnh SQL
   $delete_cart_item->execute([$user_id]);
   // Thêm thông báo thành công vào mảng message
   $success_msg[] = 'Đã xóa tất cả sản phẩm!';
}

// Kiểm tra xem có thao tác cập nhật số lượng mục trong giỏ hàng không
if (isset($_POST['update_qty'])) {
   // Lấy id mục cần cập nhật và số lượng mới từ POST
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   // Xử lý dữ liệu đầu vào để loại bỏ các ký tự không mong muốn
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   // Chuẩn bị câu lệnh SQL để cập nhật số lượng mục trong giỏ hàng
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity =? WHERE id =?");
   // Thực thi câu lệnh SQL
   $update_qty->execute([$qty, $cart_id]);
   // Thêm thông báo thành công vào mảng message
   $success_msg[] = 'Đã cập nhật số lượng!';
}

// Tính tổng giá trị của giỏ hàng
$grand_total = 0;

?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Giỏ hàng</title>

   <!-- font awesome cdn link  -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <link rel="stylesheet" href="styles_using/font.css">
   <link rel="stylesheet" href="styles_using/home_giaodien.css">
   <link rel="stylesheet" href="styles_using/oder-slider.css">
   <link rel="stylesheet" href="styles_using/aside.css">
   <link rel="stylesheet" href="styles_using/menu1.css">
   <link rel="stylesheet" href="styles_using/order1.css">
   <link rel="website icon" type="png" href="images/logo2.png.jpeg">

   <script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>


</head>

<body>

   <!-- header section starts  -->
   <?php include 'user/user_header.php'; ?>
   <?php include 'ketnoi/thongbao.php'; ?>


   <!-- header section ends -->

   <!-- <div class="heading">
      <h3>shopping cart</h3>
      <p><a href="home.php">home</a> <span> / cart</span></p>
   </div> -->

   <!-- shopping cart section starts  -->

   <!-- Bắt đầu phần hiển thị giỏ hàng -->
   <section class="products">

      <h1 class="title">your cart</h1>

      <div class="box-container">

         <?php
         // Khởi tạo tổng giá trị giỏ hàng
         $grand_total = 0;

         // Chuẩn bị truy vấn để lấy tất cả sản phẩm trong giỏ hàng của người dùng hiện tại
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id =?");
         $select_cart->execute([$user_id]);

         // Kiểm tra xem có sản phẩm nào trong giỏ hàng không
         if ($select_cart->rowCount() > 0) {
            // Duyệt qua từng sản phẩm trong giỏ hàng
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <form action="" method="post" class="box">
                  <!-- Lưu trữ id sản phẩm dưới dạng hidden input -->
                  <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">

                  <!-- Link để xem chi tiết sản phẩm -->
                  <a href="View.php?pid=<?= $fetch_cart['product_id']; ?>" class=""></a>

                  <!-- Nút xóa sản phẩm khỏi giỏ hàng -->
                  <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('Xóa sản phẩm này?');"></button>

                  <!-- Hiển thị hình ảnh sản phẩm -->
                  <img src="upload_images/<?= $fetch_cart['image']; ?>" alt="" style="height: 200px;">

                  <!-- Tên sản phẩm -->
                  <div class="name"><?= $fetch_cart['name']; ?></div>

                  <!-- Chi tiết sản phẩm -->
                  <div class="flex">
                     <!-- Giá sản phẩm -->
                     <div class="price"><?= number_format($fetch_cart['price'], 0, ',', '.'); ?> <span> đ</span></div>

                     <!-- Số lượng sản phẩm -->
                     <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2">

                     <!-- Nút cập nhật số lượng sản phẩm -->
                     <button type="submit" class="fas fa-edit" name="update_qty"></button>
                  </div>

                  <!-- Tổng giá của sản phẩm trong giỏ hàng -->
                  <div class="sub-total"> Giá tiền: <span><?= number_format($sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']), 0, ',', '.'); ?>/VNĐ</span> </div>
               </form>
         <?php
               // Cập nhật tổng giá trị giỏ hàng
               $grand_total += $sub_total;
            }
         } else {
            // Thông báo khi giỏ hàng trống
            echo ' <div class="empty_cart">
            <i class="fas fa-shopping-bag"></i>
            <h1>
                <a href="home.php"><i class="fas fa-arrow-left"></i></a>Giỏ hàng của bạn chưa có!
            </h1>
        </div>';
         }
         ?>
      </div>

      <!-- Phần tổng giá và nút mua ngay -->
      <div class="cart-total">
         <p>Tổng giá tiền : <span><?= number_format($grand_total, 0, ',', '.'); ?> đ</span></p>
         <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Mua ngay</a>
      </div>

      <!-- Phần nút xóa tất cả và tiếp tục mua -->
      <div class="more-btn">
         <form action="" method="post">
            <button type="submit" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" name="delete_all" onclick="return confirm('Xóa hết giỏ hàng?');">Xóa tất cả</button>
         </form>
         <a href="menu.php" class="btn">Tiếp tục mua</a>
      </div>

   </section>


   <!-- shopping cart section ends -->

   <!-- footer section starts  -->

   <!-- footer section ends -->








   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>