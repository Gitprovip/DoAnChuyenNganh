var swiper = new Swiper(".review-slider", {
    spaceBetween: 10, // Khoảng cách giữa các slide
    pagination: {
      el: ".swiper-pagination", // Chọn phần tử để hiển thị dấu trang
      clickable: true, // Bỏ dấu trang khi nhấp chuột
    },
  
    breakpoints: {
          1024: {
            slidesPerView: 3, // Số lượng slide hiển thị trên một hàng ở kích thước 1024px
          },
      
    },
  });
  
  ("use strict");
  
  // Cấu hình AOS để khởi động khi trang tải xong
  AOS.init({
    // Thời gian chờ trước khi bắt đầu hiệu ứng (ms)
    delay: 400,
    // Thời gian của hiệu ứng (ms)
    duration: 1000,
  });
  
  