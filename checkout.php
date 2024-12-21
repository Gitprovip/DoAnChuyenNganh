<?php

include 'ketnoi/ketnoi.php';

session_start();

// Thiết lập giá trị cho $_SESSION['shipping'] thành 30000
// Giá trị mới là 30,000đ
$_SESSION['shipping'] = '30000đ';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
};

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $address = $_POST['address'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'] ?? '';
    $total_price = $_POST['total_price'] ?? '';

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id =?");
    $check_cart->execute([$user_id]);

    // Kiểm tra xem giỏ hàng có chứa sản phẩm hay không
    if ($check_cart->rowCount() > 0) {

        // Nếu giỏ hàng không rỗng
    
        // Kiểm tra xem địa chỉ đã được nhập hay chưa
        if ($total_products == '' && $total_price == '') {
            $warning_msg[] = 'Giỏ hàng của bạn trống!';
        }
        if ($address == '') {
            $warning_msg[] = 'Hãy nhập địa chỉ của bạn nhé';
        } else {

            // Nếu địa chỉ đã được nhập
        
            // Chuẩn bị truy vấn INSERT vào bảng orders
            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
            
            // Thực hiện truy vấn INSERT
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

            // Chuẩn bị truy vấn DELETE từ bảng cart
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id =?");
            
            // Thực hiện truy vấn DELETE
            $delete_cart->execute([$user_id]);

            // Thêm thông báo thành công
            $success_msg[] = 'Cảm ơn bạn đã đặt hàng';
        }
    } else {
        // Hiển thị thông báo nếu giỏ hàng rỗng
        $warning_msg[] = 'Giỏ hàng của bạn trống';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
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
    <link rel="stylesheet" href="styles_using/checkout.css">

</head>

<body>
    <?php include 'user/user_header.php'; ?>
    <?php include 'ketnoi/thongbao.php'; ?>
    

    <h1 class="title1">Order</h1>

    <form action="" method="post">
        <div class="checkout">


            <div class="container1">

                <!-- Thông tin đơn hàng -->
                <div class="billing_details">
                    <h1 align="center">Thông tin người dùng</h1>

                    <!-- Thông tin đơn hàng -->
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><b>Tên của bạn <span class="text-danger">*</span></b></label><br>
                                <input type="text" name="name" class="form-control" value="<?= $fetch_profile['name'] ?>" readonly />

                            </div>
                        </div>


                    </div>

                    <!-- Thông tin email và số điện thoại -->
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><b>Email <span class="text-danger">*</span></b></label><br>
                                <input type="text" name="email" class="form-control" value="<?= htmlspecialchars($fetch_profile['email']) ?>" readonly />
                            </div>
                        </div>


                        <div class="col-6">
                            <div class="form-group">
                                <label><b>Số điện thoại <span class="text-danger">*</span></b></label>
                                <input type="number" name="number" class="form-control" value="<?= $fetch_profile['number'] ?>" readonly />

                            </div>
                        </div>
                    </div>

                    <!-- Thông tin địa chỉ -->
                    <div class="form-group">
                        <label><b>Địa chỉ <span class="text-danger">*</span></b></label>
                        <textarea name="address" class="form-control" readonly><?= $fetch_profile['address'] ?></textarea>
                    </div>


                    <hr style="margin-top:1.5em;" />
                    <select name="method" class="box" required style="width: 270px;
                                height: 40px; background-color: #f8f9fa; font-size: 15px;
                                border: 1px solid #ced4da; border-radius: 5px; padding: 5px 10px;">
                        <option value="" disabled selected style="color: #6c757d;">Chọn phương thức thanh toán</option>
                        <option value="Tiền mặt">Tiền mặt</option>
                        <option value="Tiền mặt">Thanh toán mã QR</option>
                    </select>



                    <div class="place_order_section">

                        <a href="update_address.php" class="btn btn-success">Cập nhật địa chỉ</a>
                        <input type="submit" value="Đặt hàng" class="btn btn-success <?php if ($fetch_profile['address'] == '') {
                                                                                            echo 'disabled';
                                                                                        } ?>" name="submit">
                    </div>
                </div>
    </form>

    <!-- Người dùng đặt hàng -->
    <div class="order_details">
        <h1 align="center">Thông tin mua hàng</h1>
        <div class="table-responsive" id="order_table">
            <table class="table table-bordered table-striped">
                <tr align="center">
                    <th>Sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá tiền</th>
                    <th>Tổng giá tiền</th>
                </tr>

                <?php
                // Khởi tạo tổng giá tiền
                $grand_total = 0;

                // Mảng để lưu trữ thông tin mục giỏ hàng
                $cart_items = []; // Changed to an empty array

                // Chuẩn bị truy vấn để tìm nạp tất cả các sản phẩm trong giỏ hàng của người dùng hiện tại
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id =?");
                $select_cart->execute([$user_id]);

                // Kiểm tra xem có bất kỳ sản phẩm nào trong giỏ hàng không
                if ($select_cart->rowCount() > 0) {
                    // Lặp qua từng sản phẩm trong giỏ hàng
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        // Thêm thông tin sản phẩm vào mảng $cart_items
                        $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ')';
                        $total_products = implode(', ', $cart_items); // Join array with commas

                        // Tính tổng giá tiền của tất cả sản phẩm trong giỏ hàng
                        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                ?>

                        <tr>
                            <td class="product_name">
                                <div class="image">
                                    <img src="upload_images/<?= $fetch_cart['image']; ?>" alt="<?php echo $fetch_cart['name']; ?>">
                                </div>
                            </td>

                            <td><?php echo $fetch_cart["name"] ?></td>
                            <td><?php echo $fetch_cart["quantity"] ?></td>
                            <td align="right"><?= number_format($fetch_cart['price'], 0, ',', '.'); ?><span>đ</span></td>
                            <td align="right"><b><?php echo number_format($fetch_cart['price'] * $fetch_cart['quantity'], 0, ',', '.') . 'đ'; ?></b></td>

                        </tr>



                        <input type="hidden" name="total_products" value="<?= $total_products; ?>">
                        <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
                <?php
                    }

                    // Tính phí vận chuyển dựa trên tổng giá tiền
                    $shipping_value = ($grand_total > 100000) ? 0 : 30000;
                } else {
                    // Thông báo khi giỏ không có sản phẩm
                    echo '<tr><td colspan="5" align="center">Giỏ hàng của bạn trống!</td></tr>';
                    $shipping_value = 0; // Set shipping value to 0
                }

                // Cập nhật tổng giá tiền với phí vận chuyển
                $grand_total += $shipping_value;

                // Chuyển đổi $grand_total thành chuỗi đã định dạng
                $formatted_grand_total = number_format($grand_total, 0, ',', '.');

                // Chuyển đổi phí vận chuyển thành chuỗi đã định dạng
                $formatted_shipping_value = number_format($shipping_value, 0, ',', '.');
                ?>
                <tr>
                    <td colspan="4" align="right"><i class="fas fa-shipping-fast"></i> <b>Phí vận chuyển</td>
                    <td align="right"><b><?php echo $formatted_shipping_value . 'đ'; ?></b></td>
                </tr>
                <tr>
                    <td colspan="4" align="right"><b>Tổng giá tiền</b></td>
                    <td align="right" style="color:red;"><b><?php echo $formatted_grand_total . 'đ'; ?></b></td>
                </tr>
            </table>
        </div>

    </div>


    <script src="js/script1.js"></script>
</body>

</html>