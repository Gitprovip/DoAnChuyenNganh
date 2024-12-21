<head>

    <script src="https://kit.fontawesome.com/95e5404ede.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles_using/main.css">
    <link rel="stylesheet" href="styles_using/profile_setting.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    

    <style>
        input[type="search"]::-webkit-search-cancel-button {
            -webkit-appearance: none;
            appearance: none;
        }

        .dropdown-menu li a:hover {
            background-color: rgb(1, 34, 25);
            color: white;
        }

        header {
            background-color: rgb(194, 115, 13);
            color: black;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .menu {
            display: flex;
            gap: 15px;
        }

        .menu a {
            text-decoration: none;
            padding: 5px 10px;
        }

        .menu a:hover {
            border-radius: 5px;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            .menu {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }

            .menu a {
                width: 100%;
                text-align: left;
            }
        }

        @media (max-width: 480px) {
            .logo {
                font-size: 18px;
            }

            .menu {
                gap: 5px;
            }
        }
    </style>

</head>
<html>
<div class="layout">
    <div class="layout-body">
        <header>
            <div class="logo">
                <a href="home.php"><img src="upload_images/95-scaled.jpg"></a>
            </div>
            <div class="menu">
                <li><a href="home.php">Trang Chủ</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="order.php">Đơn hàng</a></li>
                <li><a href="lienhe.php">Liên Hệ</a></li>
                

            </div>

            <div class="search">
                <form method="post" action="search.php">
                    <input type="search" name="search_box" placeholder="Tìm kiếm sản phẩm..." required>
                    <button id="timkiem" name="search_btn"><a href=""><i class="fa-solid fa-magnifying-glass" style="color: rgba(0, 0, 0, 0.6); font-weight: bold;font-size: 17px ;"></i></a></button>
                </form>
            </div>

            <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
            ?>
<?php 
if (!isset($_SESSION['email'])) {
    // Nếu chưa đăng nhập
    echo '
    <div class="other">
        <button style="position: relative;">
            <a href="user_cart.php" style="display: inline-block;">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
            <span style="position: absolute; top: -10px; font-size: 12px; line-height: 1; color: white;">
                (' . htmlspecialchars($total_cart_items, ENT_QUOTES, 'UTF-8') . ')
            </span>
        </button> 
        <button id="users">
            <i class="fa-solid fa-user"></i>
        </button>
    </div>';
} else {
    // Nếu đã đăng nhập
    $email = $_SESSION['email']; // Lấy ID người dùng từ session
    echo '
    <div class="other">
        <button style="position: relative;">
            <a href="user_cart.php" style="display: inline-block;">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
            <span style="position: absolute; top: -10px; font-size: 12px; line-height: 1; color: white;">
                (' . htmlspecialchars($total_cart_items, ENT_QUOTES, 'UTF-8') . ')
            </span>
        </button> 
        <button id="users">
       <i class="fa-solid fa-user"></i> ' . htmlspecialchars($email) . '
            
        </button>
    </div>';
}
?>
            <div class="menu1">
                <ul>
                    <li><a href="user_profile.php"><i class="fas fa-user"></i>&nbsp;Profile</a></li>
                    <li><a href="ketnoi/user_logout.php" onclick="return confirm('Bạn có muốn thoát không?');"><i class="fas fa-sign-out-alt"></i>&nbsp;Thoát</a></li>
                    <li><a href="admin/admin_login.php"><i class="fa-solid fa-user-tie"></i></i>&nbsp;Admin</a></li>
                </ul>
                <div class="profile">
                    <?php
                    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                    $select_profile->execute([$user_id]);
                    if ($select_profile->rowCount() > 0) {
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <?php
                    } else {
                    ?>
                    <?php
                    }
                    ?>

                    <script>
                        let profile = document.querySelector('#users');
                        let menu1 = document.querySelector('.menu1');
                        let isLoggedIn = <?= json_encode(isset($fetch_profile)) ?>; // Đặt cờ đăng nhập

                        profile.onclick = function() {
                            if (!isLoggedIn) {
                                window.location.href = '../doannam4/user/user_login.php'; // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
                            } else {
                                let profile = document.querySelector('#users');
                                let menu1 = document.querySelector('.menu1');

                                profile.onclick = function() {
                                    menu1.classList.toggle('active');
                                }
                            }
                        }
                    </script>

        </header>
    </div>
</div>
</div>




</html>