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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View</title>

   <!-- font awesome cdn link  -->


   <!-- custom css file link  -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="styles_using/font.css">
   <link rel="stylesheet" href="styles_using/home_giaodien.css">
   <link rel="stylesheet" href="styles_using/oder-slider.css">


</head>



<?php include 'user/user_header.php'; ?>
<?php include 'ketnoi/thongbao.php'; ?>
<br><br><br><br><br><br>

<?php
// Lấy id sản phẩm từ query string
$pid = $_GET['pid'];

// Chuẩn bị truy vấn để lấy thông tin sản phẩm dựa trên id
$select_products = $conn->prepare("SELECT * FROM `products` WHERE id =?");
// Thực thi truy vấn với tham số là id sản phẩm
$select_products->execute([$pid]);

// Kiểm tra xem có sản phẩm nào được trả về không
if ($select_products->rowCount() > 0) {
   // Duyệt qua từng sản phẩm được trả về
   while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
?>




      <form action="" method="post">
         <?= $fetch_products['id']; ?>
         <?= $fetch_products['price']; ?>


         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">


         <!-- Link đến danh mục sản phẩm tương ứng -->
         <a href="category.php?category=<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></a>


         <div id="alert" class="toast-container position-fixed top-0 end-0 p-3"></div>

         <div id="product-info" class="container  mt-4">
            <div class="row">
               <div id="content" class="col">

                  <div class="row mb-3">
                     <div class="col-sm">
                        <div class="image magnific-popup">
                           <a href="#" title=""> <img src="upload_images/<?= $fetch_products['image']; ?>" title="" alt="" class="img-thumbnail mb-3" /></a>
                           <p> <a href="category.php?category=<?= $fetch_products['category']; ?>"><span style="font-size: 20px; font-weight:bold; color: grey;"><?= $fetch_products['category']; ?></span>
                           </a>
                           </p>
                        </div>
                     </div>
                     <div class="col-sm po-relative">
                        <h1 class="product-name"><?= $fetch_products['name']; ?></h1>
                        <h3>
                           <p> <?= number_format($fetch_products['price'], 0, ',', '.'); ?>
                        </h3>
                        </p>
                        <div class="coupon_box">
                           <div class="coupon_slider"></div>
                           <div class="coupon_box_detail"></div>
                        </div>
                        <div class="call_us_box call_social">
                           Chat ngay:
                           <a href="" class="fas fa-comment-dots"></a>
                        </div>


                        <div id="product">
                           <div id="form-product">
                              <div class="mb-3">
                                 <p class="mb-0" style="font-size: 15px;"><label for="input-quantity" class="form-label">Số Lượng</label></p>
                                 <input type="number" name="qty" value="1" size="2" id="input-quantity" class="form-control" style="width: 50px;" />
                                 <button type="submit" id="button-cart" name="add_to_cart" class="btn btn-primary btn-lg btn-block">Thêm vào giỏ hàng</button>
                              </div>
                           </div>
                        </div>
      </form>

      <div class="pdp-featured">
         <div class="pdp-featured-item">
            <span>Giao NHANH trong 60 phút</span>
         </div>
         <div class="pdp-featured-item">
            <span>Hỗ trợ 24/24</span>
         </div>
      </div>
      </div>
      <ul class="nav nav-tabs">
         <li class="nav-item" style="font-size: 20px;"><a href="#" data-bs-toggle="tab" class="nav-link active">Mô tả sản phẩm</a></li>
      </ul>
      <div class="tab-content">
         <div id="tab-description" class="tab-pane fade show active mb-4">
            <p>Mây đeii coffee shop xin hân hạnh được phục vụ quý khách.
            </p>
         </div>
      </div>




<?php
   }
} else {
   // Thông báo khi không có sản phẩm nào
   echo '<p class="empty">no products added yet!</p>';
}
?>

</section>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>

<style>
   .img-thumbnail {
      padding: .25rem;
      background-color: #fff;
      border: 1px solid #dee2e6;
      border-radius: .25rem;
      height: 350px;
      width: auto;
   }
</style>


<style>
   /* Toàn bộ container */
   .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      font-family: 'Montserrat', sans-serif;
      color: #333;
   }

   /* Tiêu đề và thông báo trên cùng */
   .header-notice {
      text-align: center;
      color: #d0021b;
      font-weight: 600;
      margin-top: 100px;
   }

   /* Breadcrumb */
   .breadcrumb {
      display: flex;
      list-style: none;
      padding: 0;
      margin-bottom: 20px;
   }

   .breadcrumb-item {
      margin-right: 5px;
   }

   .breadcrumb-item a {
      text-decoration: none;
      color: #666;
   }

   .breadcrumb-item a:hover {
      color: #d0021b;
   }

   /* Sản phẩm */
   .product-name {
      font-size: 32px;
      color: #000;
      font-weight: 700;
      margin-bottom: 15px;
   }

   .call_us_box {
      font-size: 16px;
      margin-bottom: 15px;
      font-weight: 700;

   }

   .call-us {
      font-size: 20px;
      color: #d0021b;
      font-weight: bold;
   }

   .call_social img {
      height: 30px;
      margin-right: 10px;
   }

   /* Hình ảnh sản phẩm */
   .image img {
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      height: 350px;
      width: auto;
   }

   /* Nút đặt hàng */
   #button-cart,
   #dat-nhanh {
      background-color: #f58220;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      margin-right: 10px;
   }

   #button-cart:hover,
   #dat-nhanh:hover {
      background-color: #e6731f;
   }

   /* Tính năng nổi bật */
   .pdp-featured {
      display: flex;
      gap: 20px;
      margin-top: 20px;
   }

   .pdp-featured-item {
      display: flex;
      align-items: center;
      gap: 10px;
   }

   .pdp-featured-item img {
      width: 40px;
   }

   .pdp-featured-item span {
      font-size: 14px;
      color: #333;
   }

   /* Tabs mô tả sản phẩm */
   .nav-tabs {
      border-bottom: 2px solid #ddd;
   }

   .nav-tabs .nav-link {
      padding: 10px 15px;
      font-weight: 600;
      color: #666;
      border: none;
   }

   .nav-tabs .nav-link.active {
      color: #d0021b;
      border-bottom: 2px solid #d0021b;
   }

   .tab-content {
      margin-top: 15px;
      font-size: 14px;
      line-height: 1.6;
   }

   /* Lưu ý */
   .tab-content em {
      font-style: italic;
      color: #d0021b;
   }

   /* Đặt bố cục hai cột: hình ảnh bên trái và nội dung bên phải */
   .row.mb-3 {
      display: flex;
      align-items: flex-start;
      /* Đảm bảo các nội dung căn đều trên cùng */
      gap: 30%;
      /* Khoảng cách giữa hình ảnh và nội dung */
   }

   /* Cột hình ảnh */
   .row.mb-3 .col-sm:first-child {
      flex: 0 0 auto;
      /* Kích thước cố định cho cột ảnh */
      max-width: 40%;
      /* Đảm bảo ảnh chiếm 40% chiều rộng */
   }

   /* Cột nội dung nằm về bên phải */
   .col-sm.po-relative {
      flex: 1;
      /* Chiếm toàn bộ không gian còn lại */
      max-width: 60%;
      /* Đảm bảo nội dung chiếm 60% chiều rộng */
   }

   /* Căn chỉnh chi tiết bên trong nội dung */
   .col-sm.po-relative {
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      padding: 10px;
   }
</style>

</html>