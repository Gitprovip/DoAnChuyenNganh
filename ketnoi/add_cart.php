<?php

// Kiểm tra xem có thao tác thêm sản phẩm vào giỏ hàng không
if(isset($_POST['add_to_cart'])){

   // Kiểm tra xem người dùng đã đăng nhập chưa
   if($user_id == ''){
      // Nếu chưa đăng nhập, chuyển hướng người dùng đến trang đăng nhập
      header('location:../doannam4/user/user_login.php');
   }else{
      // Nếu đã đăng nhập, tiếp tục xử lý

      // Lấy thông tin sản phẩm từ form
      $pid = $_POST['pid'];
      // Xử lý dữ liệu đầu vào để loại bỏ các ký tự không mong muốn
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      // Kiểm tra xem sản phẩm đã có trong giỏ hàng của người dùng chưa
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name =? AND user_id =?");
      $check_cart_numbers->execute([$name, $user_id]);

      // Nếu sản phẩm đã có trong giỏ hàng, hiển thị thông báo
      if($check_cart_numbers->rowCount() > 0){
         $warning_msg[] = 'Sản phẩm đã thêm rồi';
      }else{
         // Nếu chưa có, thêm sản phẩm vào giỏ hàng
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, product_id, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         // Hiển thị thông báo thành công
         $success_msg[] = 'Đã thêm sản phẩm';
         
      }

   }

}

?>
