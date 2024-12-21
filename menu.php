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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles_using/home.css">
    <link rel="stylesheet" href="styles_using/view.css">
    <link rel="stylesheet" href="styles_using/menu2.css">
    <link rel="stylesheet" href="styles_using/font.css">
    <link rel="stylesheet" href="styles_using/oder-slider.css">
    <link rel="website icon" type="png" href="images/logo2.png.jpeg">

    <script src="https://unpkg.com/magic-snowflakes/dist/snowflakes.min.js"></script>
    
<!-- custom css file link  -->

</head>
<body>
    <?php include 'user/user_header.php';?>
    <?php include 'ketnoi/thongbao.php';?>


    <div id="carouselExample" class="carousel slide pointer-event">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="upload_images/i.png" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><image href="upload_images/1.png " width="100%" height="100%" preserveAspectRatio="xMidYMid slice" /></svg>
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
 




<section class="products">
<h1 class="title">Menu</h1>

<section class="view">
   <!-- Bắt đầu phần hiển thị danh sách Menu -->
<div class="box-container">

<?php
   // Chuẩn bị truy vấn để lấy tất cả Menu từ cơ sở dữ liệu
   $select_products = $conn->prepare("SELECT * FROM `products`");
   
   // Thực thi truy vấn
   $select_products->execute();
   
   // Kiểm tra xem có Menu nào được trả về không
   if($select_products->rowCount() > 0){
      // Duyệt qua từng Menu được trả về
      while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
?>
<form action="" method="post" class="box">
   <!-- Lưu trữ thông tin Menu dưới dạng hidden input -->
   <input type="hidden" name="pid" value="<?= $fetch_products['id'];?>">
   <input type="hidden" name="name" value="<?= $fetch_products['name'];?>">
   <input type="hidden" name="price" value="<?= $fetch_products['price'];?>">
   <input type="hidden" name="image" value="<?= $fetch_products['image'];?>">
   
   <!-- Link để xem chi tiết Menu -->
   <a href="View.php?pid=<?= $fetch_products['id'];?>" class="fas fa-eye"></a>
   
   <!-- Nút thêm Menu vào giỏ hàng -->
   <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
   
   <!-- Hiển thị hình ảnh Menu -->
   <img src="upload_images/<?= $fetch_products['image'];?>" alt=""style="height: 200px;">
   
   <!-- Link đến danh mục Menu tương ứng -->
   <a href="category.php?category=<?= $fetch_products['category'];?>" class="cat"><?= $fetch_products['category'];?></a>
   
   <!-- Tên Menu -->
   <div class="name"><?= $fetch_products['name'];?></div>

   <div class="mb-3">

                            <!-- Các ngôi sao đánh giá -->

    						<i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
	    				</div>
   
   <!-- Giá Menu -->
   <div class="flex">
       <div class="price"><span></span><?= number_format($fetch_products['price'], 0, ',', '.');?><span> đ</span></div>
       
       <!-- Số lượng muốn mua -->
       <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
   </div>
   
   <!-- Nút thêm Menu vào giỏ hàng -->
   <button type="submit" name="add_to_cart" class="cart-btn">Thêm vào giỏ hàng</button>

</form>
<?php
      }
   }else{
      // Thông báo khi không có Menu nào
      echo '<p class="empty">no products added yet!</p>';
   }
?>
</div>
<!-- Kết thúc phần hiển thị danh sách Menu -->

   

</section>
</section>
<?php include 'ketnoi/user_footer.php';?>

<script src="js/script.js"></script>

</body>

</html>