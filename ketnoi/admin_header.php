
<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin</a>

      <nav class="navbar">
         <a href="dashboard.php">Trang chủ</a>
         <a href="products.php">Sản Phẩm</a>
         <a href="place_order.php">Đơn hàng</a>
         <a href="admin_account.php">Quản trị</a>
         <a href="users_account.php">Khách hàng</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
        <?php
        $select_profile_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
        $select_profile_admin->execute([$admin_id]);
        $fetch_profile_admin = $select_profile_admin  ->fetch(PDO::FETCH_ASSOC);
        ?>
        <p> Xin chào: <?= $fetch_profile_admin['name']; ?></p>
     
         <a href="../admin/admin_login.php" onclick="return confirm('Bạn có chắc muốn thoát không?');" class="delete-btn">Thoát</a>
         <a href="../home.php" onclick="return confirm('Bạn có chắc muốn đến trang user không?');" class="delete-btn">Về trang người dùng</a>
      </div>
      </div>
    


   </section>

</header>