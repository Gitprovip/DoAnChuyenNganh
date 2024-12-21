// Lấy phần tử DOM đại diện cho profile của người dùng
let profile = document.querySelector("#users");
// Lấy phần tử DOM đại diện cho menu

let menu = document.querySelector(".menu1");

// Thêm sự kiện click vào phần tử profile
profile.onclick = function () {
  // Sử dụng phương thức toggleClass để bật/tắt lớp 'active' cho phần tử menu
  menu.classList.toggle("active");
};
