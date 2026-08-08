L-Manager

L-Manager là một trình quản lý file chạy trên nền web, hỗ trợ quản lý dữ liệu trực tiếp trên server thông qua giao diện trình duyệt.

Dự án được phát triển với mục tiêu tạo một file manager nhẹ, dễ triển khai, có thể sử dụng cho hosting cá nhân hoặc server riêng.

Tính năng

- 📁 Quản lý thư mục và file
- 📄 Xem nội dung file trực tiếp trên trình duyệt
- ✏️ Chỉnh sửa file mã nguồn
- ⬆️ Upload file
- ⬇️ Download file
- 📦 Nén và giải nén dữ liệu
- 🗑️ Xóa, đổi tên file/thư mục
- 🔐 Phân quyền file/thư mục (chmod)
- 🌐 Hỗ trợ WebDAV
- 🎨 Giao diện quản lý hiện đại
- 📱 Tương thích thiết bị di động

Công nghệ sử dụng

Backend

- PHP 7+
- WebDAV
- Apache / Nginx

Frontend

- HTML5
- CSS3
- JavaScript
- jQuery
- Highlight.js (hiển thị code)

Cài đặt

1. Clone project

git clone https://github.com/linh0804/l-manager.git

2. Cấu hình server

Yêu cầu:

- PHP >= 7.0
- Apache hoặc Nginx
- Quyền ghi cho thư mục cần quản lý

Ví dụ:

chmod -R 755 l-manager

3. Cấu hình Web Server

Đưa thư mục "l-manager" vào thư mục public của web server.

Ví dụ:

/var/www/html/l-manager

Sau đó truy cập:

https://your-domain.com/l-manager

WebDAV

L-Manager hỗ trợ truy cập file thông qua WebDAV.

Ví dụ:

https://your-domain.com/l-manager/index.php/webdav.php

Có thể kết nối bằng:

- Windows Explorer
- macOS Finder
- Linux File Manager
- Các ứng dụng WebDAV Client

Bảo mật

Khuyến nghị:

- Không public trực tiếp nếu chưa bật xác thực.
- Giới hạn quyền truy cập thư mục.
- Sử dụng HTTPS.
- Không cho phép upload file thực thi PHP nếu không cần thiết.

Trạng thái dự án

🚧 Dự án đang trong quá trình phát triển.

Các tính năng mới đang được tiếp tục bổ sung và tối ưu.

Đóng góp

Nếu bạn muốn đóng góp:

1. Fork repository
2. Tạo branch mới

git checkout -b feature/new-feature

3. Commit thay đổi

git commit -m "Add new feature"

4. Push branch

git push origin feature/new-feature

5. Tạo Pull Request

License

Dự án được phát triển cho mục đích cá nhân và có thể được mở rộng theo nhu cầu sử dụng.

---

Developed by **Nguyen Ngoc Linh**

Thanks to **Ngatngay aka Phetuyco** for inspiration and support.
