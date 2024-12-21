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
   header('location:home.php');
};

// Kiểm tra xem có thao tác submit form không
if (isset($_POST['submit'])) {

   // Lấy tên từ POST và xử lý dữ liệu đầu vào
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   // Lấy số điện thoại từ POST và xử lý dữ liệu đầu vào
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);

   // Kiểm tra nếu tên không rỗng
   if (!empty($name)) {
      // Chuẩn bị và thực thi câu lệnh SQL để cập nhật tên người dùng
      $update_name = $conn->prepare("UPDATE `users` SET name =? WHERE id =?");
      $update_name->execute([$name, $user_id]);
   }

   // Kiểm tra nếu số điện thoại không rỗng
   if (!empty($number)) {
      // Chuẩn bị và thực thi câu lệnh SQL để kiểm tra số điện thoại đã tồn tại chưa
      $select_number = $conn->prepare("SELECT * FROM `users` WHERE number =?");
      $select_number->execute([$number]);
      // Nếu số điện thoại đã tồn tại
      if ($select_number->rowCount() > 0) {
         $warning_msg[] = 'Số điện thoại đã tồn tại';
      } else {
         // Nếu số điện thoại chưa tồn tại, chuẩn bị và thực thi câu lệnh SQL để cập nhật số điện thoại
         $update_number = $conn->prepare("UPDATE `users` SET number =? WHERE id =?");
         $update_number->execute([$number, $user_id]);
      }
   }

   // Xác minh mật khẩu cũ và mới
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709'; // Hash chuỗi rỗng
   // Chuẩn bị và thực thi câu lệnh SQL để lấy mật khẩu cũ
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id =?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   // Lấy mật khẩu cũ từ POST và xử lý dữ liệu đầu vào
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   // Lấy mật khẩu mới từ POST và xử lý dữ liệu đầu vào
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   // Lấy mật khẩu xác nhận từ POST và xử lý dữ liệu đầu vào
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   // Kiểm tra mật khẩu cũ
   if ($old_pass != $empty_pass) {
      // Kiểm tra mật khẩu cũ có đúng không
      if ($old_pass != $prev_pass) {
         $warning_msg[] = 'Mật khẩu cũ không đúng';
      } elseif ($new_pass != $confirm_pass) {
         $warning_msg[] = 'Mật khẩu không trùng khớp';
      } else {
         // Kiểm tra mật khẩu mới
         if ($new_pass != $empty_pass) {
            // Chuẩn bị và thực thi câu lệnh SQL để cập nhật mật khẩu
            $update_pass = $conn->prepare("UPDATE `users` SET password =? WHERE id =?");
            $update_pass->execute([$confirm_pass, $user_id]);
            $success_msg[] = 'Mật khẩu đã được thay đổi!';
         } else {
            $warning_msg[] = 'Nhập mật khẩu mới đi bạn ey';
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cập nhật thông tin</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="styles_using/font.css">
   <link rel="stylesheet" href="styles_using/home_giaodien.css">
   <link rel="stylesheet" href="styles_using/oder-slider.css">
   <link rel="website icon" type="png" href="images/logo2.png.jpeg">

   <script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>
   <script>
        function limitInput(event) {
            var input = event.target;
            var maxLength = 10;
            var currentLength = input.value.length;
            var currentValue = input.value;
            var firstChar = currentValue.charAt(0);
            if (event.target.value.length > maxLength) {
                event.preventDefault(); // Ngăn chặn việc nhập thêm
                alert("Bạn chỉ được nhập tối đa 10 số.");
            } else if (firstChar !== '0' && currentLength === 1) {
                // Nếu chưa, ngăn chặn hoàn toàn việc nhập số đầu tiên nếu nó không phải là số 0
                event.preventDefault();
            } else if (currentLength > maxLength) {
                // Nếu đã nhập quá 10 số, ngăn chặn việc nhập thêm
                event.preventDefault();
            } else if (currentLength === maxLength && currentValue.slice(1) !== '0123456789') {
                // Nếu đã nhập đủ 10 số nhưng không phải là số điện thoại hợp lệ, ngăn chặn việc nhập thêm
                event.preventDefault();
            }
            // Kiểm tra xem người dùng đã nhập hai số 0 liên tiếp chưa
            else if (currentLength > 1 && currentValue.charAt(currentLength - 1) === '0' && currentValue.charAt(currentLength - 2) === '0') {
                event.preventDefault();
                input.value = currentValue.slice(0, -1); // Remove the last entered zero
            }

            // Kiểm tra xem người dùng đang cố gắng nhập một ký tự không phải là số chưa
            else if (typeof event.key === "string" && !/\d/.test(event.key)) {
                event.preventDefault(); // Ngăn chặn việc nhập ký tự không phải số
                alert("Chỉ được nhập số từ 0 đến 9.");
            }
        }

        // Hàm kiểm tra khi người dùng nhấp vào nút Đăng ký
        function checkNumber() {
            var numberInput = document.querySelector('input[name="number"]').value;
            var regex = /^\d{10}$/; // Kiểm tra xem có phải là 10 số hay không
            if (!regex.test(numberInput)) {
                alert("Bạn phải nhập số điện thoại đủ 10 số.");
                return false; // Tránh hành động mặc định của form
            }
            return true; // Cho phép gửi form nếu đã đúng
        }
   </script>
</head>

<body>
   <?php include 'user/user_header.php'; ?>
   <?php include 'ketnoi/thongbao.php'; ?>
  
   <section class="form-container">
      <form method="post" onsubmit="return checkNumber()">
         <h3>Cập nhật thông tin</h3>
         <input type="text" name="name" placeholder="Tên của bạn" class="box" maxlength="50" required>
         <input type="number" name="number" placeholder="Số điện thoại của bạn" class="box" onkeypress="limitInput(event)" maxlength="10" required>
         <input type="password" name="old_pass" placeholder="Nhập mật khẩu cũ của bạn" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" required>
         <input type="password" name="new_pass" placeholder="Nhập mật khẩu mới của bạn" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" required>
         <input type="password" name="confirm_pass" placeholder="Nhập lại mật khẩu mới của bạn" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" required>
         <input type="submit" value="Cập nhật" name="submit" class="btn">
         <input type="submit" value="Quay về trang profile" onclick="navigateToProfile()" class="btn">
         <p></p>
      </form>

   </section>

   <script src="js/script.js"></script>

   <script>
      function navigateToProfile() {
         window.location.href = 'user_profile.php';
      }
   </script>

</body>

</html>