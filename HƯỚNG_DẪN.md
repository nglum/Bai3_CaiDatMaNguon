# Cine Cinema - Hệ Thống Quản Lý và Đặt Vé Phim Trực Tuyến

## 📋 Mô Tả Dự Án

Đây là một hệ thống quản lý và đặt vé xem phim toàn diện cho cụm rạp kỹ thuật số (Cine Cinema). Hệ thống bao gồm hai phân hệ chính:

1. **Trang Khách Hàng**: Xem phim, chọn suất chiếu, chọn ghế và đặt vé
2. **Trang Quản Trị Admin**: Quản lý phim, lịch chiếu, và hóa đơn

## 🛠️ Công Nghệ Sử Dụng

- **Frontend**: HTML5, CSS3, JavaScript Vanilla
- **Backend**: PHP 7+
- **Database**: MySQL
- **Server**: Apache/Nginx

## 📁 Cấu Trúc Thư Mục

```
cine-ticket-booking/
├── index.php                 # Trang chủ
├── config/
│   └── database.php          # Cấu hình kết nối DB
├── pages/
│   ├── booking.php           # Trang đặt vé khách hàng
│   └── admin/
│       ├── login.php         # Trang đăng nhập admin
│       ├── dashboard.php     # Bảng điều khiển admin
│       ├── movies.php        # Quản lý phim
│       ├── showtimes.php     # Quản lý suất chiếu
│       ├── bookings.php      # Quản lý hóa đơn
│       ├── logout.php        # Đăng xuất
│       └── auth.php          # Xử lý xác thực
├── api/
│   ├── movies.php            # API phim
│   ├── genres.php            # API thể loại
│   ├── showtimes.php         # API suất chiếu
│   └── bookings.php          # API hóa đơn
├── helpers/
│   └── functions.php         # Hàm hỗ trợ
├── assets/
│   ├── css/
│   │   ├── style.css         # CSS chính
│   │   ├── booking.css       # CSS trang đặt vé
│   │   ├── admin.css         # CSS admin
│   │   └── responsive.css    # CSS responsive
│   └── js/
│       ├── main.js           # JS trang chủ
│       ├── booking.js        # JS đặt vé
│       ├── admin.js          # JS admin dashboard
│       ├── admin-movies.js   # JS quản lý phim
│       ├── admin-showtimes.js# JS quản lý suất chiếu
│       ├── admin-bookings.js # JS quản lý hóa đơn
│       └── admin-login.js    # JS đăng nhập
├── database.sql              # Script tạo database
└── README.md                 # File này
```

## 🗄️ Cơ Sở Dữ Liệu

### Bảng chính:

1. **genres**: Thể loại phim
2. **movies**: Danh sách phim
3. **showtimes**: Lịch chiếu (suất chiếu)
4. **bookings**: Hóa đơn đặt vé
5. **booking_items**: Chi tiết vé (danh sách ghế)

## ⚙️ Cài Đặt & Chạy

### 1. Tạo Database

```bash
# Mở MySQL CLI
mysql -u root -p

# Chạy script database
source path/to/database.sql;
```

### 2. Cập Nhật Cấu Hình

Mở file `config/database.php` và cập nhật:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'cine_ticket_db');
```

### 3. Chạy Server

```bash
# Dùng PHP built-in server
php -S localhost:8000

# Hoặc dùng Apache/Nginx
```

### 4. Truy Cập

- **Trang chủ**: http://localhost:8000/index.php
- **Admin**: http://localhost:8000/pages/admin/login.php
  - Username: `admin`
  - Password: `admin123`

## 🎯 Các Chức Năng Chính

### Phân Hệ Khách Hàng

✅ **Xem danh sách phim**
- Lọc theo thể loại
- Tìm kiếm theo tên
- Lọc theo ngày chiếu

✅ **Đặt vé 4 bước**
1. Chọn phim
2. Chọn suất chiếu & ghế
3. Nhập thông tin khách hàng
4. Xác nhận & hoàn tất

✅ **Sơ đồ ghế động**
- Hiển thị ghế trống, đã đặt, đã chọn
- Kiểm tra tùng lập tức trùng ghế

### Phân Hệ Admin

✅ **Quản lý Phim (CRUD)**
- Thêm, sửa, xóa phim
- Quản l�� thể loại
- Cập nhật trạng thái

✅ **Quản lý Suất Chiếu**
- Xếp lịch chiếu
- Kiểm tra trùng lịch phòng
- Cập nhật giá vé

✅ **Quản lý Hóa Đơn**
- Xem danh sách hóa đơn
- Chi tiết vé (danh sách ghế)
- Cập nhật trạng thái: Chờ thanh toán → Đã thanh toán → Đã hủy

✅ **Dashboard**
- Thống kê tổng phim, suất chiếu, hóa đơn
- Tính toán doanh thu

## 🔒 Bảo Mật

- Xác thực đơn giản (có thể nâng cấp)
- Kiểm tra input trên cả client & server
- Sử dụng prepared statements (tránh SQL injection)
- Session handling cho admin

## 📱 Responsive Design

- Tối ưu hóa cho desktop, tablet, mobile
- CSS Grid & Flexbox
- Media queries

## 🚀 Cải Tiến Tương Lai

- [ ] Đăng nhập OAuth (Google, Facebook)
- [ ] Thanh toán online (Stripe, PayPal)
- [ ] Email confirmation
- [ ] QR code vé
- [ ] Admin analytics
- [ ] Khuyến mãi & discount codes
- [ ] Rating & reviews phim

## 📞 Hỗ Trợ

Nếu có lỗi, vui lòng:
1. Kiểm tra cấu hình database
2. Xem console browser (F12)
3. Kiểm tra error logs

---

**Tác giả**: Sinh viên Công Nghệ Phần Mềm  
**Ngày tạo**: 2026-06-01
