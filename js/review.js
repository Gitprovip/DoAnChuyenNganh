// --------------------- Reviews --------------------------

$(document).ready(function () {
  // Khi tài liệu HTML đã tải xong và DOM đã sẵn sàng, đoạn mã bên trong hàm này sẽ được thực thi

  var rating_data = 0;
  // Khởi tạo biến 'rating_data' với giá trị ban đầu là 0, dùng để lưu trữ giá trị đánh giá (rating) của người dùng

  // Khi người dùng nhấp vào nút 'add_review', hiển thị hộp thoại modal để thêm đánh giá
  $("#add_review").click(function () {
    $("#review_modal").modal("show");
    // Hiển thị modal với id 'review_modal' khi người dùng nhấp vào nút 'add_review'
  });

  // Khi di chuột vào biểu tượng ngôi sao, màu của ngôi sao sẽ thay đổi thành màu vàng
  $(document).on("mouseenter", ".submit_star", function () {
    // Lấy giá trị 'data-rating' từ ngôi sao mà người dùng di chuột vào
    var rating = $(this).data("rating");

    // Đặt lại màu nền của các ngôi sao từ vàng thành xám nếu người dùng đã chọn và muốn thay đổi đánh giá
    reset_background();

    // Thay đổi màu nền của các ngôi sao từ màu đầu tiên đến ngôi sao được chọn thành màu vàng
    for (var count = 1; count <= rating; count++) {
      $("#submit_star_" + count).addClass("text-warning");
      // Thêm lớp 'text-warning' để đổi màu của ngôi sao thành màu vàng
    }
  });

  // Hàm đặt lại màu nền của các ngôi sao từ vàng về xám
  function reset_background() {
    for (var count = 1; count <= 5; count++) {
      $("#submit_star_" + count).addClass("star-light");
      // Thêm lớp 'star-light' để đổi màu của ngôi sao thành màu xám nhạt

      $("#submit_star_" + count).removeClass("text-warning");
      // Loại bỏ lớp 'text-warning' để ngôi sao không còn màu vàng
    }
  }

  // Khi người dùng nhấp vào một ngôi sao, lưu giá trị đánh giá vào biến 'rating_data'
  $(document).on("click", ".submit_star", function () {
    rating_data = $(this).data("rating");
    // Lưu giá trị đánh giá của ngôi sao được chọn vào biến 'rating_data'
  });

  // Khi người dùng rời khỏi ngôi sao mà không chọn, đặt lại màu và hiển thị lại các ngôi sao đã chọn
  $(document).on("mouseleave", ".submit_star", function () {
    // Đặt lại màu của tất cả các ngôi sao về màu xám
    reset_background();

    // Thay đổi màu của các ngôi sao đã chọn thành màu vàng nếu đã có đánh giá
    for (var count = 1; count <= rating_data; count++) {
      $("#submit_star_" + count).removeClass("star-light");
      // Loại bỏ lớp 'star-light' để ngôi sao không còn màu xám nhạt

      $("#submit_star_" + count).addClass("text-warning");
      // Thêm lớp 'text-warning' để ngôi sao đổi thành màu vàng
    }
  });

  // Khi người dùng nhấp vào nút 'save_review', gửi dữ liệu đánh giá đến máy chủ bằng yêu cầu Ajax
  $("#save_review").click(function () {
    var user_name = $("#user_name").val();
    // Lấy giá trị tên người dùng từ input có id 'user_name'

    var user_review = $("#user_review").val();
    // Lấy giá trị đánh giá của người dùng từ input có id 'user_review'

    // Kiểm tra xem tên người dùng và nội dung đánh giá có rỗng hay không
    if (user_name == "" || user_review == "") {
      Swal.fire({
        position: "center",
        icon: "error",
        title: "Nhập nội dung vào",
        showConfirmButton: false,
        timer: 2000,
      });
      // Hiển thị thông báo lỗi nếu tên người dùng hoặc nội dung đánh giá trống
      return false;
    } else if (rating_data == 0) {
      // Kiểm tra xem người dùng có chọn đánh giá hay không
      Swal.fire({
        position: "center",
        icon: "error",
        title: "Hãy đánh giá nhé",
        showConfirmButton: false,
        timer: 2000,
      });
      // Hiển thị thông báo lỗi nếu người dùng chưa chọn đánh giá
      return false;
    } else {
      // Gửi dữ liệu đánh giá đến máy chủ bằng yêu cầu Ajax
      $.ajax({
        url: "./requests/submit_rating.php",
        // URL của tập lệnh PHP xử lý dữ liệu đánh giá

        method: "POST",
        // Phương thức HTTP được sử dụng để gửi dữ liệu

        data: {
          
          rating_data: rating_data,
          user_name: user_name,
          user_review: user_review,
          action: "submit_rating",
        },
        // Dữ liệu sẽ được gửi đến máy chủ bao gồm giá trị đánh giá, tên người dùng, đánh giá và hành động 'submit_rating'

        success: function (data) {
          // Khi dữ liệu đã được gửi thành công và phản hồi được trả về từ máy chủ
          $("#review_modal").modal("hide");
          // Ẩn modal đánh giá sau khi gửi dữ liệu

          Swal.fire({
            position: "center",
            icon: "success",
            title: data,
            showConfirmButton: false,
            timer: 2000,
          }).then(() => {
            // Sử dụng then() để đợi modal SweetAlert2 đóng trước khi tải lại trang
            location.reload();
            // Tải lại trang sau khi thông báo thành công
          });

          // Đặt lại giá trị của các input trong modal
          $("#user_name").val("");
          $("#user_review").val("");
          reset_background();

          // Cập nhật lại dữ liệu đánh giá sau khi gửi
          load_rating_data();
        },
      });
    }
  });

  // Khi người dùng nhấp vào nút 'cancel_review', ẩn modal đánh giá
  $("#cancel_review").click(function () {
    $("#review_modal").modal("hide");
    // Ẩn modal đánh giá khi người dùng nhấp vào nút 'cancel_review'
  });

  // Cập nhật dữ liệu đánh giá khi trang được mở
  load_rating_data();

  function load_rating_data() {
    // Gửi yêu cầu Ajax để tải dữ liệu đánh giá từ máy chủ
    $.ajax({
      url: "./requests/submit_rating.php",
      // URL của tập lệnh PHP xử lý dữ liệu đánh giá

      method: "POST",
      // Phương thức HTTP được sử dụng để gửi yêu cầu

      data: { action: "load_data" },
      // Dữ liệu gửi đi chỉ bao gồm hành động 'load_data' để yêu cầu tải dữ liệu

      dataType: "JSON",
      // Định dạng dữ liệu mà chúng ta mong đợi từ máy chủ là JSON

      success: function (data) {
        // Khi dữ liệu được tải thành công từ máy chủ

        // Hiển thị đánh giá trung bình và tổng số đánh giá
        $("#average_rating").text(data.average_rating);
        $("#total_review").text(data.total_review);

        var count_star = 0;

        // Thay đổi màu nền các ngôi sao dựa trên số sao trung bình
        $(".main_star").each(function () {
          count_star++;
          if (Math.floor(data.average_rating) >= count_star) {
            $(this).addClass("text-warning");
            $(this).addClass("star-light");
            // Thêm lớp 'text-warning' và 'star-light' cho các ngôi sao tương ứng với đánh giá trung bình
          }
        });

        // Hiển thị số lượng đánh giá cho mỗi mức đánh giá (1-5 sao)
        $("#total_five_star_review").text(data.five_star_review);
        $("#total_four_star_review").text(data.four_star_review);
        $("#total_three_star_review").text(data.three_star_review);
        $("#total_two_star_review").text(data.two_star_review);
        $("#total_one_star_review").text(data.one_star_review);

        // Cập nhật thanh tiến trình tương ứng với tỷ lệ các đánh giá
        $("#five_star_progress").css(
          "width",
          (data.five_star_review / data.total_review) * 100 + "%"
        );
        $("#four_star_progress").css(
          "width",
          (data.four_star_review / data.total_review) * 100 + "%"
        );
        $("#three_star_progress").css(
          "width",
          (data.three_star_review / data.total_review) * 100 + "%"
        );
        $("#two_star_progress").css(
          "width",
          (data.two_star_review / data.total_review) * 100 + "%"
        );
        $("#one_star_progress").css(
          "width",
          (data.one_star_review / data.total_review) * 100 + "%"
        );

        // Hiển thị danh sách các đánh giá
        if (data.review_data.length > 0) {
          var html = "";

          for (var count = 0; count < data.review_data.length; count++) {
            // Tạo HTML cho mỗi đánh giá và thêm vào carousel
            html += '<div class="swiper-slide slide" >';
            html += '<i class="fas fa-quote-right"></i>';
            html += '<div class="user">';
            html +=
              '<div class="col-sm-2"><div class="rounded-circle pt-2 pb-2"><h2 class="text-center">' +
              data.review_data[count].user_name.charAt(0) +
              "</h2></div></div>";

            html += '<div class="user-info">';
            html += "<h3>" + data.review_data[count].user_name + "</h3>";
            html += '<div class="stars">';

            for (var star = 1; star <= 5; star++) {
              var class_name = "";

              if (data.review_data[count].rating >= star)
                class_name = "text-warning";
              else class_name = "star-light2";
              // Đổi màu ngôi sao dựa trên đánh giá của người dùng

              html += '<i class="fas fa-star ' + class_name + ' mr-1"></i>';
            }

            html += "</div>";
            html += "<h4>" + data.review_data[count].datetime + "</h4>";
            html += "</div></div>";

            html += "<p>" + data.review_data[count].user_review + "</p>";
            html += "</div>";
          }

          $("#reviews_swiper").html(html);
          // Thêm HTML đánh giá vào carousel hiển thị trên trang
        }
      },
    });
  }
});
