<?php


include '../ketnoi/ketnoi.php';


session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['pass'] ?? '';
    $errors = [];

    try {

        if (empty($email)) $errors[] = "Tài khoản không được để trống.";
        if (empty($password)) $errors[] = "Mật khẩu không được để trống.";


            // Nếu không có lỗi, kiểm tra thông tin trong cơ sở dữ liệu
            if (empty($errors)) {
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                // Kiểm tra tài khoản và mật khẩu
                if ($user && $password === $user['password']) {
                    // Lưu thông tin vào session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                 

                    // Chuyển hướng sau khi đăng nhập thành công
                    echo "<script> window.location.href = '../home.php';</script>";
                    exit;
                } else {
                    $errors[] = "Tài khoản hoặc mật khẩu không đúng.";
                }
            }

    } catch (PDOException $e) {
        $errors[] = "Lỗi hệ thống: " . $e->getMessage();
    }
}



$errors = []; // Mảng lưu lỗi đăng ký

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enter'])) {
    $SGname = $_POST['SGname'] ?? '';
    $SGemail = $_POST['SGemail'] ?? '';
    $number = $_POST['SGnumber'] ?? '';
    $SGpassword = $_POST['SGpass'] ?? '';
    $confirm_password = $_POST['cpass'] ?? '';

    try {

        if (empty($SGname)) $errors[] = "Tên không được để trống.";
        if (empty($SGemail)) $errors[] = "Email không được để trống.";
        if (empty($number)) $errors[] = "Số điện thoại không được để trống.";
        if (empty($SGpassword)) $errors[] = "Mật khẩu không được để trống.";
        if ($SGpassword !== $confirm_password) $errors[] = "Mật khẩu nhập lại không khớp.";

        // Kiểm tra email và số điện thoại hợp lệ
        if (!filter_var($SGemail, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";
        if (!preg_match('/^[0-9]{10}$/', $number)) $errors[] = "Số điện thoại phải đủ 10 số.";

        // Nếu không có lỗi, kiểm tra xem email  đã tồn tại chưa
        if (empty($errors)) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? ");
            $stmt->execute([$SGemail]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Email hoặc số điện thoại đã tồn tại.";
            } else {
               
                $stmt = $conn->prepare("INSERT INTO users (name, email, number, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$SGname, $SGemail, $number, $SGpassword]);

                // Đăng ký thành công, chuyển hướng đến trang đăng nhập
                echo "<script>alert('Đăng ký thành công!'); window.location.href = 'user_login.php';</script>";
                exit;
            }
        }
    } catch (PDOException $e) {
        $errors[] = "Lỗi hệ thống: " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập và đăng ký</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link rel="stylesheet" href="../styles_using/login.css">

</head>

<body>
    <?php include '../ketnoi/thongbao.php'; ?>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="post" onsubmit="return checkNumber()">
                <h2>Đăng ký tài khoản</h2>
                <div class="social-icons">
                    <a href="https://accounts.google.com/v3/signin/identifier?continue=https%3A%2F%2Fwww.google.com%2Fsearch%3Fq%3Dgmail%26oq%3Dgmail%26gs_lcrp%3DEgZjaHJvbWUyBggAEEUYOTIHCAEQABiPAjIHCAIQABiPAtIBCDEzNTJqMGo0qAIAs
                    AIA%26sourceid%3Dchrome%26ie%3DUTF-8&ddm=0&ec=futura_gmv_dt_so_72586115_e&flowEntry=ServiceLogin&flowName=GlifWebSignIn&hl=vi&ifkv=Ab5oB3r-RcXwWYRZhxRV2HVm3CQtL3z2mOcmrQAegAdInYJnozLw89lzCuMt-IFyaZCpr6TpCiHE
                    DA&passive=true&dsh=S-585539894%3A1725128268500185" class="icon"><i class="fa-brands fa-google"></i></a>

                    <a href="https://www.facebook.com/?locale=vi_VN" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/" class="icon"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://x.com/?lang=vi" class="icon"><i class="fa-brands fa-twitter"></i></a>
                </div>
                  <!-- Hiển thị lỗi nếu có -->
        <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error) : ?>
                <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
                <input type="text" placeholder="Tên của bạn" name="SGname" required>
                <input type="email" placeholder="Email" name="SGemail" required>
                <input type="text" placeholder="Số điện thoại" name="SGnumber"  maxlength="10" required />
                <input type="password" placeholder="Mật khẩu" name="SGpass" required>
                <input type="password" placeholder="Nhập lại mật khẩu" name="cpass" required>
                <button name="enter">Đăng ký</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form  action="user_login.php" method="post">
            <?php if (!empty($errors)) : ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error) : ?>
                                <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                <h1>Đăng Nhập</h1>
                <div class="social-icons">
                    <a href="https://accounts.google.com/v3/signin/identifier?continue=https%3A%2F%2Fwww.google.com%2Fsearch%3Fq%3Dgmail%26oq%3Dgmail%26gs
                    _lcrp%3DEgZjaHJvbWUyBggAEEUYOTIHCAEQABiPAjIHCAIQABiPAtIBCDEzNTJqMGo0qAIAsAIA%26sourceid%3Dchrome%26ie%3DUTF-8&ddm=0&ec=futura_gmv_dt_so
                    _72586115_e&flowEntry=ServiceLogin&flowName=GlifWebSignIn&hl=vi&ifkv=Ab5oB3r-RcXwWYRZhxRV2HVm3CQtL3z2mOcmrQAegAdInYJnozLw89lzCuMt-IFyaZ
                    Cpr6TpCiHEDA&passive=true&dsh=S-585539894%3A1725128268500185" class="icon"><i class="fa-brands fa-google"></i></a>

                    <a href="https://www.facebook.com/?locale=vi_VN" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/" class="icon"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://x.com/?lang=vi" class="icon"><i class="fa-brands fa-twitter"></i></a>
                </div>
                <input type="email" placeholder="Email" name="email" required>
                <input type="password" placeholder="Mật khẩu" name="pass" required>
                <a href="../home.php">Quay về trang chủ</a>
                <button name="submit">Đăng nhập</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Chào mừng bạn</h1>
                    <p>Nhập thông tin cá nhân của bạn để sử dụng tất cả các tính năng của trang web</p>
                    <button class="hidden" id="login">Đăng nhập</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Chào bạn</h1>
                    <p>Chào mừng bạn đến với </p>
                    <button class="hidden" id="register">Đăng ký</button>
                    <button class="hidden" id="register"><a href="../admin/admin_login.php">Đăng nhập Admin</a></button>
                </div>
            </div>
        </div>
    </div>

    <script src="../javascript/user_login1.js"></script>
</body>
</html>