<?php
include 'ketnoi/ketnoi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
function checkLoginAndRedirect() {
    // Kiểm tra xem người dùng đã đăng nhập chưa
    var userId = "<?php echo isset($_SESSION['user_id'])? $_SESSION['user_id'] : '';?>";
    if (userId === '') {
        window.location.href = 'user/user_login.php'; // Chuyển hướng đến trang đăng nhập
    }
}

// Gán sự kiện click cho nút "Đánh giá"
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('add_review').addEventListener('click', function(event) {
        event.preventDefault(); // Ngăn chặn hành vi mặc định của button
        checkLoginAndRedirect(); // Kiểm tra trạng thái đăng nhập
    });
});
</script>
</head>
<body>
<div class="menu-tabs" data-aos="fade-up"></div>
<section class="review" id="review">

    <h3 class="sub-heading2" data-aos="fade-up" style="text-align:center"> Đánh giá của khách hàng </h3>
    <h1 class="heading2" data-aos="fade-up" style="text-align:center"></h1>


    <!-- In this section we used bootstrap for the design, and JQUERY... -->
    <div   div class="container1">
    	<div class="card1">
    		<div class="card-body1">
    			<div class="row">

    				<div class="col-sm-4 text-center">

                        <!-- Hiển thị điểm đánh giá trung bình và số lượng đánh giá -->
    					<h1 class="text-warning mt-4 mb-4">

    						<b><span id="average_rating">0.0</span> / 5</b>
    					</h1>
    					<div class="mb-3">

                            <!-- Các ngôi sao đánh giá -->

    						<i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
	    				</div>
    					<h3><span id="total_review">0</span> Đánh giá</h3>
    				</div>

    				<div class="col-sm-4">

                    <!-- Hiển thị tiến trình đánh giá theo số sao -->
                    <p>
                            <div class="progress-label-left"><b>5</b> <i class="fas fa-star text-warning"></i></div>

                            <div class="progress-label-right">(<span id="total_five_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="five_star_progress"></div>
                            </div>
                        </p>
    					<p>
                            <div class="progress-label-left"><b>4</b> <i class="fas fa-star text-warning"></i></div>
                            
                            <div class="progress-label-right">(<span id="total_four_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="four_star_progress"></div>
                            </div>               
                        </p>
    					<p>
                            <div class="progress-label-left"><b>3</b> <i class="fas fa-star text-warning"></i></div>
                            
                            <div class="progress-label-right">(<span id="total_three_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="three_star_progress"></div>
                            </div>               
                        </p>
    					<p>
                            <div class="progress-label-left"><b>2</b> <i class="fas fa-star text-warning"></i></div>
                            
                            <div class="progress-label-right">(<span id="total_two_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="two_star_progress"></div>
                            </div>               
                        </p>
    					<p>
                            <div class="progress-label-left"><b>1</b> <i class="fas fa-star text-warning"></i></div>
                            
                            <div class="progress-label-right">(<span id="total_one_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="one_star_progress"></div>
                            </div>               
                        </p>
                        <!-- Lặp lại cho các mức đánh giá khác (4 sao, 3 sao, v.v.) -->
    				</div>

    				<div class="col-sm-4 text-center">
                        <!-- Hỗ trợ viết đánh giá -->
    					<h3 class="mt-4 mb-3">Viết đánh giá ở đây</h3>
    					<button type="button" name="add_review" id="add_review" class="btn">Đánh giá</button>
    				</div>

    			</div>
    		</div>
    	</div>
    </div>   

    <div class="swiper-container review-slider">
            <div class="swiper-wrapper" id="reviews_swiper">

            </div>

    </div>



</section>


<!-- Pop up modal for review -->
<div id="review_modal" class="modal fade" tabindex="-1" role="dialog">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
	      	<div class="modal-header">
	        	<h5 class="modal-title">Đánh giá</h5>
	      	</div>
	      	<div class="modal-body">
	      		<h4 class="text-center mt-2 mb-4 star_rating">
	        		<i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
	        	</h4>
	        	<div class="form-group review_input">
	        		<input type="hidden" name="user_name" id="user_name" class="form-control" value="<?php echo $fetch_profile['name']; ?>" />
	        	</div>
	        	<div class="form-group review_input">
	        		<textarea name="user_review" id="user_review" class="form-control" placeholder="Nhập nội dung đánh giá"></textarea>
	        	</div>
	        	<div class="form-group text-center mt-4">
	        		<button type="button" class="btn" id="save_review">Xác nhận</button>
                    <button type="button" class="btn btn-default" id="cancel_review">Hủy bỏ</button>
	        	</div>
	      	</div>
    	</div>
  	</div>
</div>

</body>
</html>
