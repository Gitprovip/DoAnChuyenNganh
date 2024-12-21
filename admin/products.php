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

// Kiểm tra xem có yêu cầu thêm sản phẩm hay không
if (isset($_POST['add_product'])) {
   // Lấy tên sản phẩm từ form và lọc xóa các ký tự không hợp lệ
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);


   $detail_product = $_POST['detail_product'];
   $detail_product = filter_var($detail_product, FILTER_SANITIZE_STRING);


   // Xóa dấu phẩy khỏi giá và lọc xóa các ký tự không phải số
   $price = $_POST['price'];
   $price = str_replace(',', '', $price);
   $price = filter_var($price, FILTER_SANITIZE_NUMBER_INT);

   // Lấy danh mục từ form và lọc xóa các ký tự không hợp lệ
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   // Lấy hình ảnh từ form, lọc xóa các ký tự không hợp lệ, và thiết lập đường dẫn lưu trữ
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../upload_images/' . $image;

   // Kiểm tra xem sản phẩm có tồn tại trong cơ sở dữ liệu chưa
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name =? OR image =?");
   $select_products->execute([$name, $image]);
   if ($select_products->rowCount() > 0) {
      // Nếu sản phẩm đã tồn tại, thêm thông báo lỗi
      $warning_msg[] = 'Sản phẩm đã tồn tại';
   } else {
      // Kiểm tra kích thước hình ảnh
      if ($image_size > 2000000) {
         // Nếu kích thước quá lớn, thêm thông báo lỗi
         $error_msg[] = 'Kích thước ảnh quá lớn';
      } else {
         // Nếu kích thước hợp lệ, di chuyển hình ảnh lên thư mục lưu trữ và thêm sản phẩm vào cơ sở dữ liệu
         move_uploaded_file($image_tmp_name, $image_folder);
         $insert_product = $conn->prepare("INSERT INTO `products`(name, detail_product, category, price, image) VALUES(?,?,?,?,?)");
         $insert_product->execute([$name, $detail_product, $category, $price, $image]);
         // Thêm thông báo thành công
         $success_msg[] = 'Sản phẩm đã được thêm';
      }
   }
}

// Kiểm tra xem có yêu cầu xóa sản phẩm hay không
if (isset($_GET['delete'])) {
   // Lấy ID sản phẩm cần xóa từ query string
   $delete_id = $_GET['delete'];

   // Kiểm tra xem có sản phẩm nào trong giỏ hàng liên quan đến sản phẩm này không
   $check_cart = $conn->prepare("SELECT * FROM cart WHERE product_id = ?");
   $check_cart->execute([$delete_id]);

   if ($check_cart->rowCount() > 0) {
      // Nếu có sản phẩm trong giỏ hàng, xóa nó trước
      $delete_from_cart = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
      $delete_from_cart->execute([$delete_id]);

      // Hiển thị thông báo cho người dùng biết đã xóa khỏi giỏ hàng
      echo "<script>alert('Sản phẩm đã được xóa khỏi giỏ hàng');</script>";
   }

   // Lấy thông tin hình ảnh của sản phẩm cần xóa
   $delete_product_image = $conn->prepare("SELECT * FROM products WHERE id =?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);

   // Xóa hình ảnh từ hệ thống tệp
   unlink('../uploaded_img/' . $fetch_delete_image['image']);

   // Xóa sản phẩm khỏi cơ sở dữ liệu
   $delete_product = $conn->prepare("DELETE FROM products WHERE id =?");
   $delete_product->execute([$delete_id]);

   // Chuyển hướng trở lại trang sản phẩm sau khi xóa
   header('location:products.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sản phẩm</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../styles_admin/admin_giaodien.css">
   <link rel="stylesheet" href="../styles_admin/table.css">

</head>

<body>
   <?php include '../ketnoi/thongbao.php'; ?>
   <?php include '../ketnoi/admin_header.php' ?>

   <!-- add products section starts  -->

   <!-- Bắt đầu phần thêm sản phẩm -->
   <section class="add-products">

      <!-- Form để thêm sản phẩm -->
      <form action="" method="POST" enctype="multipart/form-data">
         <!-- Tiêu đề cho form -->
         <h3>Thêm sản phẩm</h3>

         <!-- Input cho tên sản phẩm, bắt buộc nhập, giới hạn độ dài tối đa 100 ký tự -->
         <input type="text" required placeholder="Nhập tên sản phẩm" name="name" maxlength="100" class="box">

         <!-- Input cho chi tiết sản phẩm, không bắt buộc nhập, Không giới hạn độ dài tối đa ký tự -->
         <input type="text" placeholder="Nhập chi tiết sản phẩm" name="detail_product" class="box">

         <!-- Input cho giá tiền, kiểu số, bắt buộc nhập, giới hạn từ 0 đến 9999999999, ngăn chặn nhập thêm ký tự khi đạt độ dài 10 ký tự -->
         <input type="number" min="0" max="9999999999" required placeholder="Nhập giá tiền" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
         
         <input type="text"  required placeholder="Nhập Loại"  name="category"  class="box" id="categoryInput" list="categorySuggestions">

         <!-- Input để chọn hình ảnh, chấp nhận các định dạng hình ảnh cụ thể, bắt buộc chọn -->
         <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>

         <!-- Button submit để gửi form, tên button là "Thêm sản phẩm" -->
         <input type="submit" value="Thêm sản phẩm" name="add_product" class="btn">
      </form>

   </section>
   <!-- Kết thúc phần thêm sản phẩm -->


   <!-- add products section ends -->

   <!-- show products section starts  -->

   <section class="show-products" style="padding-top: 0;">



      </div>
      <div>
         <table class="table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Ảnh</th>
                  <th>Tên sản phẩm</th>
                  <th>Mô tả chi tiết sản phẩm</th>
                  <th>Giá tiền</th>
                  <th>Loại</th>
                  <th>Thao tác</th>
               </tr>
            </thead>
            <!-- Bắt đầu phần hiển thị danh sách sản phẩm -->
            <tbody>
               <?php
               // Chuẩn bị câu lệnh SQL để lấy tất cả sản phẩm từ bảng `products`
               $show_products = $conn->prepare("SELECT * FROM `products`");

               // Thực thi câu lệnh SQL
               $show_products->execute();

               // Kiểm tra xem có sản phẩm nào được trả về từ cơ sở dữ liệu không
               if ($show_products->rowCount() > 0) {
                  // Duyệt qua từng sản phẩm được trả về
                  while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <!-- Hiển thị ID của sản phẩm -->
                        <td><?= $fetch_products['id']; ?></td>

                        <!-- Hiển thị hình ảnh của sản phẩm với kích thước cố định -->
                        <td><img style="height: 70px; width: 120px;" src="../upload_images/<?= $fetch_products['image']; ?>" alt=""></td>

                        <!-- Hiển thị tên của sản phẩm -->
                        <td><?= $fetch_products['name']; ?></td>

                        <!-- Hiển thị chi tiết của sản phẩm -->
                        <td class="product-detail-cell"><?= $fetch_products['detail_product']; ?></td>

                        <!-- Hiển thị giá của sản phẩm, sử dụng hàm number_format để định dạng số theo quy chuẩn Việt Nam -->
                        <td><span></span><?= number_format($fetch_products['price'], 0, ',', '.'); ?><span> đ</span></td>

                        <!-- Hiển thị danh mục của sản phẩm -->
                        <td><?= $fetch_products['category']; ?></td>

                        <!-- Hiển thị các hành động đối với sản phẩm, bao gồm cập nhật và xóa -->
                        <td>
                           <!-- Link để cập nhật sản phẩm, sử dụng icon bút để biểu thị hành động cập nhật -->
                           <a href="update_product.php?update=<?= $fetch_products['id']; ?>"><button><i class="fa-solid fa-pen-to-square"></i></button></a>

                           <!-- Link để xóa sản phẩm, sử dụng icon thùng rác để biểu thị hành động xóa, hiển thị hộp thoại xác nhận trước khi thực hiện -->
                           <a href="products.php?delete=<?= $fetch_products['id']; ?>" onclick="return confirm('Xóa sản phẩm này?');"><button><i class="fa-solid fa-trash"></i></button></a>
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  // Hiển thị thông báo nếu không có sản phẩm nào được thêm
                  echo '<p class="empty">no products added yet!</p>';
               }
               ?>
            </tbody>
            <!-- Kết thúc phần hiển thị danh sách sản phẩm -->

         </table>
      </div>

   </section>

   <!-- show products section ends -->

   <!-- custom js file link  -->
   <script src="../javascript/admin_login.js"></script>

</body>
<style>
   .product-detail-cell {
    word-wrap: break-word;     /* Cho phép từ dài bị ngắt xuống dòng */
    word-break: break-all;     /* Ngắt từng chữ cái nếu cần */
    max-width: 250px;          /* Giới hạn chiều rộng tối đa của ô */
    white-space: normal;       /* Cho phép văn bản xuống dòng */
    overflow-wrap: break-word; /* Đảm bảo từ dài được ngắt */
}
</style>
</html>