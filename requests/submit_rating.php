<?php

// Bao gồm file cấu hình kết nối database
include '../ketnoi/ketnoi.php';
// Khởi động phiên session
session_start();

// Kiểm tra xem session 'user_id' có tồn tại không
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
};

// Kiểm tra xem có hành động nào được gửi từ form không
if (isset($_POST["action"])) {

    global $conn; // Sử dụng biến toàn cục $conn, giả sử đã được định nghĩa trong file ketnoi/ketnoi.php

    // Nếu hành động là 'submit_rating', tức là gửi đánh giá
    if ($_POST["action"] == "submit_rating") {
        // Lấy dữ liệu từ form
        $username = $_POST["user_name"];
        $userRating = $_POST["rating_data"];
        $userReview = $_POST["user_review"];
        // Tạo chuỗi thời gian hiện tại dưới dạng ngày/tháng/năm
        $datetime = date('j') . "-" . date('n') . "-" . date('Y');

        // Chuẩn bị truy vấn SQL để chèn dữ liệu đánh giá vào bảng
        $stmt = $conn->prepare("INSERT INTO review_table(user_id,user_name, user_rating, user_review, datetime) VALUES (?,?,?,?,?)");
        // Thực thi truy vấn với các tham số cần thiết
        $stmt->execute([$user_id, $username, $userRating, $userReview, $datetime]);

        // Kiểm tra xem truy vấn có thành công hay không
        if ($stmt->rowCount() > 0)
            echo "Đánh giá thành công!";
        else
            echo "Đánh giá không thành công";
    }

    // Nếu hành động là 'load_data', tức là tải dữ liệu đánh giá từ database
    if ($_POST["action"] == "load_data") {
        // Khởi tạo các biến để lưu trữ dữ liệu
        // Khởi tạo các biến để lưu trữ dữ liệu
        $average_rating = 0; // Biến để lưu trữ điểm đánh giá trung bình.

        $total_review = 0; // Biến để lưu trữ tổng số đánh giá.

        $five_star_review = 0; // Biến để lưu trữ số lượng đánh giá với mức sao 5.

        $four_star_review = 0; // Biến để lưu trữ số lượng đánh giá với mức sao 4.

        $three_star_review = 0; // Biến để lưu trữ số lượng đánh giá với mức sao 3.

        $two_star_review = 0; // Biến để lưu trữ số lượng đánh giá với mức sao 2.

        $one_star_review = 0; // Biến để lưu trữ số lượng đánh giá với mức sao 1.

        $total_user_rating = 0; // Biến để lưu trữ tổng số điểm đánh giá từ tất cả các đánh giá.

        $review_content = []; // Mảng để lưu trữ nội dung đánh giá

        // Thực hiện truy vấn SQL để lấy tất cả đánh giá từ bảng
        $stmt = $conn->query("SELECT * FROM review_table ORDER BY review_id DESC");
        // Lấy tất cả các dòng trả về dưới dạng mảng liên kết
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Duyệt qua từng dòng để tính tổng số đánh giá và tổng số điểm đánh giá
        foreach ($rows as $row) {
            $review_content[] = [
                "user_name" => $row["user_name"],
                "user_review" => $row["user_review"],
                "rating" => $row["user_rating"],
                "datetime" => $row["datetime"]
            ];

            // Tăng số lượng đánh giá tương ứng với mỗi mức sao
            switch ($row["user_rating"]) {
                case '5':
                    $five_star_review++;
                    break;
                case '4':
                    $four_star_review++;
                    break;
                case '3':
                    $three_star_review++;
                    break;
                case '2':
                    $two_star_review++;
                    break;
                case '1':
                    $one_star_review++;
                    break;
            }

            // Cập nhật tổng số đánh giá và tổng số điểm đánh giá
            $total_review++;
            $total_user_rating += $row["user_rating"];
        }

        // Tính điểm đánh giá trung bình
        $average_rating = $total_user_rating / $total_review;

        // Tạo mảng output để trả về cho client
        $output = [
            // Điểm đánh giá trung bình được tính bằng cách chia tổng số điểm đánh giá cho tổng số đánh giá.
            // Hàm number_format được sử dụng để làm tròn số thập phân đến một chữ số sau dấu phẩy.
            'average_rating' => number_format($average_rating, 1),

            // Tổng số đánh giá, tức là tổng số lần đánh giá mà người dùng đã thực hiện.
            'total_review' => $total_review,

            // Số lượng đánh giá với mức sao 5.
            'five_star_review' => $five_star_review,

            // Số lượng đánh giá với mức sao 4.
            'four_star_review' => $four_star_review,

            // Số lượng đánh giá với mức sao 3.
            'three_star_review' => $three_star_review,

            // Số lượng đánh giá với mức sao 2.
            'two_star_review' => $two_star_review,

            // Số lượng đánh giá với mức sao 1.
            'one_star_review' => $one_star_review,

            // Dữ liệu chi tiết về từng đánh giá, bao gồm tên người dùng, nội dung đánh giá, số sao và thời gian đánh giá.
            'review_data' => $review_content
        ];


        // Trả về dữ liệu dưới dạng JSON
        echo json_encode($output);
    }
}
