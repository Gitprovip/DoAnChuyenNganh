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

// Kiểm tra xem có yêu cầu cập nhật sản phẩm hay không
if (isset($_POST['update'])) {
   // Lấy thông tin sản phẩm từ form và lọc xóa các ký tự không hợp lệ
   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $detail_product = $_POST['detail_product'];
   $detail_product = filter_var($detail_product, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   // Chuẩn bị câu lệnh SQL để cập nhật thông tin sản phẩm
   $update_product = $conn->prepare("UPDATE `products` SET name = ?, detail_product = ?, category = ?, price = ? WHERE id = ?");
   // Thực thi câu lệnh SQL với tham số đã được đặt
   $update_product->execute([$name, $detail_product, $category, $price, $pid]);

   // Thêm thông báo cập nhật thành công
   $success_msg[] = 'Sản phẩm đã được cập nhật';

   // Lấy hình ảnh cũ của sản phẩm
   $old_image = $_POST['old_image'];
   // Lấy thông tin mới về hình ảnh từ form, lọc xóa các ký tự không hợp lệ
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   // Lấy kích thước của hình ảnh
   $image_size = $_FILES['image']['size'];
   // Lấy tên tạm thời của hình ảnh
   $image_tmp_name = $_FILES['image']['tmp_name'];
   // Thiết lập đường dẫn lưu trữ cho hình ảnh mới
   $image_folder = '../uploaded_img/' . $image;

   // Nếu có hình ảnh mới
   if (!empty($image)) {
      // Kiểm tra kích thước hình ảnh
      if ($image_size > 2000000) {
         // Nếu kích thước quá lớn, thêm thông báo lỗi
         $warning_msg[] = 'Kích thước ảnh quá lớn';
      } else {
         // Chuẩn bị câu lệnh SQL để cập nhật hình ảnh
         $update_image = $conn->prepare("UPDATE `products` SET image =? WHERE id =?");
         // Thực thi câu lệnh SQL với tham số đã được đặt
         $update_image->execute([$image, $pid]);
         // Di chuyển hình ảnh mới lên thư mục lưu trữ
         move_uploaded_file($image_tmp_name, $image_folder);
         // Xóa hình ảnh cũ
         unlink('../uploaded_img/' . $old_image);
         // Thêm thông báo cập nhật hình ảnh thành công
         $success_msg[] = 'Cập nhật ảnh thành công';
      }
   }

   // Kiểm tra xem sản phẩm có trong cart không
   $check_in_cart = $conn->prepare("SELECT * FROM cart WHERE product_id = ?");
   $check_in_cart->execute([$pid]);

   if ($check_in_cart->rowCount() > 0) {
      // Cập nhật sản phẩm trong bảng cart
      $update_cart = $conn->prepare("UPDATE cart SET name = ?, price = ? WHERE product_id = ?");
      $update_cart->execute([$name, $price, $pid]);

      // Thêm thông báo cập nhật thành công
      $success_msg[] = 'Sản phẩm đã được cập nhật trong cả hai bảng';
   }

   // Chuyển hướng người dùng về trang sản phẩm sau khi cập nhật
   header('Location: products.php');
   exit();
}

// Hiển thị thông báo
include '../ketnoi/thongbao.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cập nhật sản phẩm</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../styles_admin/admin_giaodien.css">

</head>

<body>
   <?php include '../ketnoi/admin_header.php' ?>

   <!-- update product section starts  -->

   <!-- Bắt đầu phần cập nhật sản phẩm -->
   <section class="update-product">

      <!-- Tiêu đề cho phần cập nhật sản phẩm -->
      <h1 class="heading">Cập nhật sản phẩm</h1>

      <?php
      // Lấy ID sản phẩm cần cập nhật từ query string
      $update_id = $_GET['update'];

      // Chuẩn bị câu lệnh SQL để lấy thông tin sản phẩm cần cập nhật
      $show_products = $conn->prepare("SELECT * FROM `products` WHERE id =?");
      // Thực thi câu lệnh SQL với ID sản phẩm
      $show_products->execute([$update_id]);

      // Kiểm tra xem có sản phẩm nào được trả về từ cơ sở dữ liệu không
      if ($show_products->rowCount() > 0) {
         // Duyệt qua từng sản phẩm được trả về
         while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <form action="" method="POST" enctype="multipart/form-data">
               <!-- Hidden input để chứa ID sản phẩm -->
               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">

               <!-- Hidden input để chứa hình ảnh cũ của sản phẩm -->
               <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">

               <!-- Hiển thị hình ảnh hiện tại của sản phẩm -->
               <img src="../upload_images/<?= $fetch_products['image']; ?>" alt="">

               <!-- Input cho tên sản phẩm, bắt buộc nhập -->
               <span>Tên sản phẩm</span>
               <input type="text" required placeholder="Nhập tên sản phẩm" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">

               <!-- Input cho chi tiết sản phẩm, không bắt buộc nhập -->
               <span>Chi tiết sản phẩm</span>
               <input type="text" placeholder="Nhập chi tiết sản phẩm" name="detail_product" class="box" value="<?= $fetch_products['detail_product']; ?>">

               <!-- Input cho giá tiền, kiểu số, bắt buộc nhập -->
               <span>Giá tiền</span>
               <input type="number" min="0" max="9999999999" required placeholder="Nhập giá tiền" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">
               <input type="text"  required placeholder="Nhập Loại"  name="category"  class="box" id="categoryInput" list="categorySuggestions">
               <!-- Select dropdown cho loại sản phẩm, bắt buộc chọn -->
               <!-- <span>Loại sản phẩm</span>
               <select name="category" class="box" required>
                  <option value="SAMOYED">SAMOYED</option>
                  <option value="MÈO XÁM CHÂN NGẮN">MÈO XÁM CHÂN NGẮN</option>
                  <option value="GOLDEN VÀNG">GOLDEN VÀNG</option>
                  <option value="MÈO BICOLOR">MÈO BICOLOR</option>
                  <option value="COLLIE ĐEN TRẮNG">COLLIE ĐEN TRẮNG</option>
               </select> -->

               <!-- Input để chọn hình ảnh mới, chấp nhận các định dạng hình ảnh cụ thể -->
               <span>Cập nhật hình ảnh</span>
               <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">

               <!-- Group button để chứa nút submit -->
               <div class="flex-btn">
                  <!-- Nút submit để gửi form, tên button là "Cập nhật" -->
                  <input type="submit" value="Cập nhật" class="btn" name="update">
               </div>
            </form>
      <?php
         }
      } else {
         // Hiển thị thông báo nếu không có sản phẩm nào được thêm
         echo '<p class="empty">Chưa có sản phẩm nào được cập nhật!</p>';
      }
      ?>

   </section>
   <!-- Kết thúc phần cập nhật sản phẩm -->

   <script src="../javascript/admin_login.js"></script>

</body>

</html>