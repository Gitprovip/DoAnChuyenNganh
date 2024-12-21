// Lấy phần tử container chứa cả hai form đăng nhập và đăng ký
const container = document.getElementById("container");
// Lấy phần tử button đăng ký
const registerBtn = document.getElementById("register"); // Lưu ý: Tên ID trong code khác với bình thường
// Lấy phần tử button đăng nhập
const loginBtn = document.getElementById("login"); // Lưu ý: Tên ID trong code khác với bình thường

// Thêm sự kiện click vào button đăng ký
registerBtn.addEventListener("click", () => {
  // Thêm lớp 'active' vào container, điều này thường sẽ hiển thị form đăng ký
  container.classList.add("active");
});

// Thêm sự kiện click vào button đăng nhập
loginBtn.addEventListener("click", () => {
  // Loại bỏ lớp 'active' khỏi container, thường sẽ ẩn form đăng ký và hiển thị form đăng nhập
  container.classList.remove("active");
});
