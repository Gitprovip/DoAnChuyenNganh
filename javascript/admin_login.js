// Lấy phần tử DOM đại diện cho phần profile của header
let profile = document.querySelector(".header .flex .profile");

// Thêm sự kiện click vào button user
document.querySelector("#user-btn").onclick = () => {
  // Toggled trạng thái 'active' cho phần profile
  profile.classList.toggle("active");
  // Loại bỏ trạng thái 'active' khỏi phần navbar
  navbar.classList.remove("active");
};
