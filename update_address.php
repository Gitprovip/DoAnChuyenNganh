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

    // Lấy địa chỉ từ POST và xử lý dữ liệu đầu vào
    $address = $_POST['city'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);

    // Chuẩn bị và thực thi câu lệnh SQL để cập nhật địa chỉ người dùng
    $update_address = $conn->prepare("UPDATE `users` set address =? WHERE id =?");
    $update_address->execute([$address, $user_id]);
    // Thêm thông báo thành công vào mảng $success_msg
    $success_msg[] = 'Đã cập nhật địa chỉ';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật địa chỉ</title>
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
   




</head>

<body>
    <?php include 'user/user_header.php'; ?>
    <?php include 'ketnoi/thongbao.php'; ?>
   
    <section class="form-container">
        <form action="" method="post">
            <h3>Địa chỉ của bạn</h3>


            <input type="text" class="box" placeholder="Nhập địa chỉ của bạn" required maxlength="100" name="city">

            <input type="submit" value="Cập nhật địa chỉ" name="submit" class="btn">
            <p></p>
            <input type="submit" value="Quay về trang profile" onclick="navigateToProfile()" class="btn">
            <input type="submit" value="Đi tới trang thanh toán" onclick="navigateTocheckout()" class="btn">
        </form>

    </section>

    <script src="js/script.js"></script>
    <script>
        function navigateToProfile() {
            window.location.href = 'user_profile.php';
        }

        function navigateTocheckout() {
            window.location.href = 'checkout.php';
        }
    </script>
</body>

</html>