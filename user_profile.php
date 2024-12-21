<?php

include 'ketnoi/ketnoi.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin</title>
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
  <link rel="stylesheet" href="styles_using/profile.css">
    <style>
        
    </style>
</head>
<body>
    <?php include 'user/user_header.php' ;?>

<div class="sidenav">
        <div class="profile1">
            <img src="./images/icon-user copy.png" alt="" >

            <div class="name">
            <?php 
    if (isset($fetch_profile) && isset($fetch_profile['name'])) {
        echo $fetch_profile['name'];
    } else {
        echo "Tên không tồn tại"; // Thay thế bằng thông báo lỗi hoặc giá trị mặc định
    }
    ?>
            </div>
        </div>

        <div class="sidenav-url">
            <div class="url">
                <a href="#profile" class="active">Thông tin</a>
                <hr align ="center">
            </div>
            <div class="url">
                <a href="./update_profile.php">Sửa thông tin</a>
                <hr align  ="center">
            </div>
        </div>
    </div>
    <div class="main">
        <h2>Thông tin của bạn</h2>
        <div class="card">
            <div class="card-body">
                <table>
                    <tbody>


                    
                        <tr>
                            <td>Tên của bạn</td>
                            <td>:</td>
                            <td> <?php  echo $fetch_profile['name'];; 
                             ?>
                             </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td> <?php  echo $fetch_profile['email'];  ?></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ</td>
                            <td>:</td>
                            <td><p class="address"><i class="fas fa-map-marker-alt" style="margin-right:10px"></i><span><?php if($fetch_profile['address'] == '') {echo ' Nhập địa chỉ của bạn đi nào';}else{echo $fetch_profile['address'];} ?></span></p>
                            <a href="update_address.php" class="btn">Cập nhật địa chỉ</a></td>
                            
                        </tr>
                     
                        <tr>
                            <td>Số điện thoại</td>
                            <td>:</td>
                            <td><?php  echo $fetch_profile['number'];  ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
   </div>
    <script src="js/script.js"></script>
</body>
</html>