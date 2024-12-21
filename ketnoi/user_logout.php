<?php
// Đọc file kết nối cơ sở dữ liệu
include 'ketnoi.php';

// Bắt đầu một phiên làm việc
session_start();

// Xóa tất cả các biến trong phiên
session_unset();

// Hủy bỏ phiên làm việc hiện tại
session_destroy();

// Chuyển hướng đến trang chủ
header('location:../home.php');

?>
