<?php

include 'ketnoi/ketnoi.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

if (isset($_POST['send'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);

    $checkUserId = $conn->prepare("SELECT * FROM `users` WHERE `id` =?");
    $checkUserId->execute([$user_id]);
    $userIdExists = $checkUserId->rowCount() > 0;

    if (!$userIdExists) {
        $error_msg[] = 'Vui lòng đăng nhập.';
    } else {
        $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name =? AND email =? AND number =? AND message =?");
        $select_message->execute([$name, $email, $number, $msg]);

        if ($select_message->rowCount() > 0) {
            $warning_msg[] = 'Nội dung bạn nhập bị trùng với tin nhắn trước đó';
        } else {
            $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
            $insert_message->execute([$user_id, $name, $email, $number, $msg]);

            $success_msg[] = 'Gửi tin nhắn thành công';
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ</title>

    <!-- font awesome cdn link  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="styles_using/font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles_using/home_giaodien.css">
    <link rel="stylesheet" href="styles_using/oder-slider.css">
    <link rel="website icon" type="png" href="images/logo2.png.jpeg">

    <style>
        /***************** Team ******************/
        .teamHSK {
            text-align: center;
        }

        .col-lg-4-swiper-slide {
            flex: 1 1 250px;
            /* Mỗi slide chiếm 1 phần của container, có thể co giãn */
            margin: auto;
            /* Căn chỉnh slide ở giữa */
            text-align: center;
            /* Trung tâm nội dung bên trong slide */
        }

        .team-slider {
            display: flex;
            /* Sử dụng flexbox để căn chỉnh */
            justify-content: center;
            /* Căn chỉnh các item nằm giữa */
            align-items: center;
            /* Căn chỉnh các item theo trục dọc */
            flex-wrap: wrap;
            /* Cho phép các item.wrap nếu không đủ space */
            gap: 1em;
            /* Tạo khoảng cách giữa các item */
        }

        .team-box {
            height: 380px;
            padding: 1.5em;
            border-radius: 30px;
            background: linear-gradient(145deg, #ececec, #ffffff);

            transition: 0.8s cubic-bezier(0.22, 0.78, 0.45, 1.02);
        }

        .team-img {
            width: 85%;
            height: 200px;
            border-radius: 20px;
            margin-bottom: 20px;
            box-shadow: 9px 9px 18px rgb(194 194 194 /0.5), -9px -9px 18px rgb(255 255 255 / 0.5);
            background-position: center;
            background-size: cover;
        }

        .team-box .h3-title {
            text-transform: capitalize;
            color: #0d0d25;
            font-weight: 600;
        }

        .team-slider .social-icon {
            margin: 15px 0px 10px;
        }

        .team-slider .social-icon ul li {
            display: inline-block;
            margin: 0 6px;
        }

        .team-slider .social-icon ul li:last-child {
            margin-right: 0;
        }

        .team-slider .social-icon ul li:first-child {
            margin-left: 0;
        }

        .team-slider .social-icon ul li a {
            width: 50px;
            height: 50px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            background: linear-gradient(145deg, #e6e6e6, #ffffff);
            box-shadow: 4px 4px 8px #d0d0d0, -4px -4px 8px #ffffff;
            color: #0d0d25;
            font-size: 2rem;
        }

        .team-slider .social-icon ul li a:hover {
            background: #ff8243;
        }

        .team-slider .social-icon ul li a:hover i {
            color: #ffffff !important;
        }

        .hidden-input {
            position: absolute;
            left: -9999px;
        }
    </style>

    <script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>

</head>

<body>


    <?php include 'user/user_header.php'; ?>
    <?php include 'ketnoi/thongbao.php'; ?>
<br><br>

<br>


    <div class="container my-5 contact-container">
        
        <div class="row">
            <!-- Contact Information and Form -->
            <div class="col-md-6 contact-info">
                <h3>Liên hệ với chúng tôi</h3>
                <p><i class="fas fa-map-marker-alt"></i> 180 cao lỗ phường 4, quận 8, Tp HCM</p>
                <p><i class="fas fa-phone"></i> 0388730191</p>
                <p><i class="fas fa-envelope"></i> Đỗ Nguyễn Anh Khôi DH52103066@student.stu.edu.vn </p>        
                <h4 class="mt-4">Liên hệ với chúng tôi</h4>
                <form class="contact-form">
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Họ và tên" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" placeholder="Nội dung" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-custom">Gửi liên hệ</button>
                </form>
            </div>
    
            <!-- Google Map Embed -->
            <div class="col-md-6">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.9543420446425!2d106.67525717570298!3d10.73800245990001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f62a90e5dbd%3A0x674d5126513db295!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2jhu4cgU8OgaSBHw7Ju!5e0!3m2!1svi!2s!4v1730826294898!5m2!1svi!2s"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

</body>

</html>