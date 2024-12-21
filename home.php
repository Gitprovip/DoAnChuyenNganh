<?php

// Xác định đường dẫn đến tập tin kết nối cơ sở dữ liệu
include 'ketnoi/ketnoi.php';

// Khởi động phiên làm việc
session_start();

// Kiểm tra xem session 'user_id' đã được thiết lập
if (isset($_SESSION['user_id'])) {
    // Nếu đã được thiết lập, lấy giá trị user_id từ session
    $user_id = $_SESSION['user_id'];
} else {
    // Nếu chưa được thiết lập, đặt user_id thành rỗng
    $user_id = '';
};

// Xác định đường dẫn đến tập tin xử lý việc thêm sản phẩm vào giỏ hàng
include 'ketnoi/add_cart.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1, maximum-scale=1">
    <title>Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles_using/font.css">
    <link rel="stylesheet" href="styles_using/home_giaodien.css">
    <link rel="stylesheet" href="styles_using/oder-slider.css">
    <link rel="stylesheet" href="styles_using/aside.css">
    <link rel="website icon" type="png" href="images/logo2.png.jpeg">
    <script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>

    <style>
        /*********************** About ***********************/

        @font-face {
            font-family: fontst1;
            src: url('font/BT_Beau_Sans/BT-BeauSans-BoldItalic.ttf');
        }

        @font-face {
            font-family: fontst2;
            src: url('font/BT_Beau_Sans/BT-BeauSans-Bold.ttf');
        }

        @font-face {
            font-family: fontst3;
            src: url('font/BT_Beau_Sans/BT-BeauSans-Light.ttf');
        }

    </style>
    <script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>
    <!-- swiper css file -->
    <link rel="stylesheet" href="./plugins/swiper-8.0.7/css/swiper.min.css">
    <!-- aos css file -->
    <link rel="stylesheet" href="./plugins/aos-2.3.4/css/aos.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="./css/style1.css">

    <link rel="stylesheet" href="./css/review.css">

</head>

<body>
    <?php include 'user/user_header.php'; ?>
    <?php include 'ketnoi/thongbao.php'; ?>


    <div id="carouselExample" class="carousel slide pointer-event">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="upload_images/1.png" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><image href="upload_images/1.png " width="100%" height="100%" preserveAspectRatio="xMidYMid slice" /></svg>
        </div>
        <div class="carousel-item">
          <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholde" preserveAspectRatio="xMidYMid slice" focusable="false"><image href="upload_images/2.png " width="100%" height="100%" preserveAspectRatio="xMidYMid slice" /></svg>
        </div>
        <div class="carousel-item">
          <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false"><image href="upload_images/3.png " width="100%" height="100%" preserveAspectRatio="xMidYMid slice" /></svg>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
 
    <!-- <section class="view">
            <h1 class="section__heading text-center" style="margin-top: 3px; font-size: 50px; margin-right:-400px; margin-left:-400px;">
            <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Image" preserveAspectRatio="xMidYMid slice" focusable="false">
            <image href="upload_images/banner.jpg " width="100%" height="100%" preserveAspectRatio="xMidYMid slice" />
				</h1>
    </section> -->
    <!-- category Area -->

    <section class="products">
        <h1 class="title">Menu</h1>
        <!-- Bắt đầu phần hiển thị sản phẩm nổi bật -->
        <section class="quick-view">
            <div class="box-container">

                <?php
                // Chuẩn bị truy vấn để lấy 4 sản phẩm đầu tiên từ cơ sở dữ liệu
                $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 4");

                // Thực thi truy vấn
                $select_products->execute();

                // Kiểm tra xem có sản phẩm nào được trả về không
                if ($select_products->rowCount() > 0) {
                    // Duyệt qua từng sản phẩm được trả về
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <form action="" method="post" class="box" style="height:450px;">
                            <!-- Lưu trữ thông tin sản phẩm dưới dạng hidden input -->
                            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                            <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                            <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                            <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">

                            <!-- Link để xem chi tiết sản phẩm -->
                            <a href="View.php?pid=<?= $fetch_products['id']; ?>" class=""></a>

                            <!-- Nút thêm sản phẩm vào giỏ hàng -->
                            <button type="submit" class="" name="add_to_cart"></button>

                            <!-- Hiển thị hình ảnh sản phẩm -->
                            <img src="upload_images/<?= $fetch_products['image']; ?>" alt="" style="height: 200px;">

                            <!-- Link đến danh mục sản phẩm tương ứng -->
                            <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
                        

                            <!-- Tên sản phẩm -->
                            <div class="name"><?= $fetch_products['name']; ?></div>

                           

                            <!-- Giá sản phẩm -->
                            <div class="flex">
                                <div class="price"><span></span><?= number_format($fetch_products['price'], 0, ',', '.'); ?><span> đ</span></div>

                                <!-- Số lượng muốn mua -->
                                <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                            </div>
                            

                            <!-- Nút thêm sản phẩm vào giỏ hàng -->
                            <button type="submit" name="add_to_cart" class="cart-btn">Thêm vào giỏ hàng</button>

                        </form>
                <?php
                    }
                } else {
                    // Thông báo khi không có sản phẩm nào
                    echo '<p class="empty">Sản phẩm chưa thêm</p>';
                }
                ?>

            </div>
            </div>
            <!-- Kết thúc phần hiển thị sản phẩm nổi bật -->

            <!-- Bắt đầu phần hiển thị nút xem tất cả sản phẩm -->
            <div class="more-btn">
                <a href="menu.php" class="btn">Xem tất cả</a>
            </div>
        </section>
        <!-- Kết thúc phần hiển thị nút xem tất cả sản phẩm -->

    

        <!-- Kết thúc phần hiển thị sản phẩm nổi bật -->


    <!-- Kết thúc phần hiển thị tin tức mới nhất -->



    </section>
    <?php include 'review.php'; ?>

    
    <?php include 'ketnoi/user_footer.php'; ?>


</body>
<style>
    .products .box-container1 {
        display: grid;
        /* grid-template-columns: repeat(auto-fit, 33rem); */
        grid-template-columns: repeat(4, 33rem);
        justify-content: center;
        align-items: flex-start;
        gap: 1.5rem;
    }

    .products .box-container1 .box {
        /* border: var(--border); */
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        height: 450px;
        background-color: #f8f4f4ad;
    }

    .products .box-container1 .box img {
        height: 450px;
        width: 100%;
        display: block;
        object-fit: contain;
        margin-bottom: 1rem;
        border-radius: 15px;
        overflow: hidden;

    }
</style>

</html>