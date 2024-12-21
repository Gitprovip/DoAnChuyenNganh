<?php

include 'ketnoi/ketnoi.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'ketnoi/add_cart.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Menu</title>

   <!-- font awesome cdn link  -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles_using/font.css">
    <link rel="stylesheet" href="styles_using/home.css">
    <link rel="stylesheet" href="styles_using/menu1.css">
    <link rel="stylesheet" href="styles_using/oder-slider.css">
    <link rel="stylesheet" href="styles_using/view.css">
    <link rel="website icon" type="png" href="images/logo2.png.jpeg">

    <script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>
    <style>
      .products .box{
         transform: translateY(30px);
      }
    </style>
   </head>
<body>
   
<?php include 'user/user_header.php'; ?>
<?php include 'ketnoi/thongbao.php';?>

    <section class="view">
<section class="products">

   <h1 class="title">Menu </h1>

   <div class="box-container">

   <?php
    // Lấy danh mục sản phẩm từ query string
    $category = $_GET['category'];
    
    // Chuẩn bị truy vấn để lấy tất cả sản phẩm thuộc một danh mục cụ thể
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE category =?");
    // Thực thi truy vấn với tham số là danh mục sản phẩm
    $select_products->execute([$category]);
    
    // Kiểm tra xem có sản phẩm nào được trả về không
    if($select_products->rowCount() > 0){
        // Duyệt qua từng sản phẩm được trả về
        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
?>
            <form action="" method="post" class="box">
                <!-- Lưu trữ thông tin sản phẩm dưới dạng hidden input -->
                <input type="hidden" name="pid" value="<?= $fetch_products['id'];?>">
                <input type="hidden" name="name" value="<?= $fetch_products['name'];?>">
                <input type="hidden" name="detail_product" value="<? $fetch_products['detail_product'];?>">
                <input type="hidden" name="price" value="<?= $fetch_products['price'];?>">
                <input type="hidden" name="image" value="<?= $fetch_products['image'];?>">
                
                <!-- Link để xem chi tiết sản phẩm -->
                <a href="View.php?pid=<?= $fetch_products['id'];?>" class="fas fa-eye"></a>
                
                <!-- Nút thêm sản phẩm vào giỏ hàng -->
                <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
                
                <!-- Hiển thị hình ảnh sản phẩm -->
                <img src="upload_images/<?= $fetch_products['image'];?>" alt="" style="height: 200px;">
                
                <!-- Link đến danh mục sản phẩm tương ứng -->
                <a href="category.php?category=<?= $fetch_products['category'];?>" class="cat"><?= $fetch_products['category'];?></a>
                
                <!-- Tên sản phẩm -->
                <div class="name"><?= $fetch_products['name'];?></div>
                
                <!-- Chi tiết sản phẩm -->
                <div class="flex">
                    <!-- Giá sản phẩm -->
                    <div class="price"><span></span><?= number_format($fetch_products['price'], 0, ',', '.');?><span> vnđ</span></div>
                    
                    <!-- Số lượng muốn mua -->
                    <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                </div>
                
                <!-- Nút thêm sản phẩm vào giỏ hàng -->
                <button type="submit" name="add_to_cart" class="cart-btn">Thêm vào giỏ hàng</button>
            </form>
<?php
        }
    }else{
        // Thông báo khi không có sản phẩm nào
        echo '<p class="empty">Sản phẩm chưa có</p>';
    }
?>


</section>
</section>


<?php include 'ketnoi/user_footer.php';?>

















<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>


</body>
</html>