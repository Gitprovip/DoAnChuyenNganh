<?php

include 'ketnoi/ketnoi.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

include 'ketnoi/add_cart.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles_using/search.css">
    <link rel="stylesheet" href="styles_using/home.css">
    <link rel="stylesheet" href="styles_using/font.css">
    <link rel="stylesheet" href="styles_using/view.css">
    <link rel="stylesheet" href="styles_using/oder-slider.css">
    <link rel="website icon" type="png" href="images/logo2.png.jpeg">

    <script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>
    <style>
        .products .box {
            transform: translateY(70px);
        }
    </style>

</head>

<body>
    <?php include 'ketnoi/thongbao.php'; ?>
    <?php include 'user/user_header.php'; ?>

    <section class="view">
        <section class="products" style="padding-top:50px;">

            <div class="box-container">

                <?php
                // Kiểm tra xem có thao tác từ hộp tìm kiếm hoặc nút tìm kiếm được nhấn không
                if (isset($_POST['search_box']) or isset($_POST['search_btn'])) {
                    // Lấy giá trị từ hộp tìm kiếm
                    $search_box = $_POST['search_box'];

                    // Chuẩn bị truy vấn để tìm kiếm sản phẩm dựa trên tên
                    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'");
                    // Thực thi truy vấn
                    $select_products->execute();
                } else {
                    // Nếu không có tìm kiếm, lấy tất cả sản phẩm
                    $select_products = $conn->prepare("SELECT * FROM `products`");
                    $select_products->execute();
                }

                // Kiểm tra xem có sản phẩm nào phù hợp với điều kiện tìm kiếm không
                if ($select_products->rowCount() > 0) {
                    // Duyệt qua từng sản phẩm phù hợp
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <form action="" method="post" class="box">
                            <!-- Lưu trữ thông tin sản phẩm dưới dạng hidden input -->
                            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                            <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                            <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                            <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">

                            <!-- Link để xem nhanh sản phẩm -->
                            <a href="view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>

                            <!-- Nút thêm sản phẩm vào giỏ hàng -->
                            <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>

                            <!-- Hiển thị hình ảnh sản phẩm -->
                            <img src="upload_images/<?= $fetch_products['image']; ?>" alt="">

                            <!-- Link đến danh mục sản phẩm tương ứng -->
                            <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>

                            <!-- Tên sản phẩm -->
                            <div class="name"><?= $fetch_products['name']; ?></div>

                            <!-- Chi tiết sản phẩm -->
                            <div class="flex">
                                <div class="price"><span></span><?= number_format($fetch_products['price'], 0, ',', '.'); ?><span> đ</span></div>
                                <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                            </div>

                            <button type="submit" name="add_to_cart" class="cart-btn">Thêm vào giỏ hàng</button>
                        </form>
                <?php
                    }
                } else {
                    echo '<p class="empty">Không có thông tin bạn tìm kiếm</p>';
                }

                ?>


            </div>

        </section>
    </section>
    <script src="js/script.js"></script>



</body>

</html>