# 🎬 Cine Cinema - Hệ Thống Quản Lý và Đặt Vé Phim Trực Tuyến

**Dự án bài tập lớn môn Công nghệ Phần mềm**

---

## 📋 Mục Lục

1. [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
2. [Cài đặt và chạy](#-cài-đặt-và-chạy)
3. [Kiến trúc dự án](#-kiến-trúc-dự-án)
4. [Cơ sở dữ liệu](#-cơ-sở-dữ-liệu)
5. [Hướng dẫn sử dụng](#-hướng-dẫn-sử-dụng)
6. [API Documentation](#-api-documentation)
7. [Giải quyết lỗi](#-giải-quyết-lỗi)

---

## ⚙️ Yêu Cầu Hệ Thống

Trước khi cài đặt, đảm bảo bạn đã cài:

- **PHP 7.0+** (khuyến nghị PHP 8.0+)
- **MySQL 5.7+** hoặc **MariaDB 10.3+**
- **Apache** hoặc **Nginx** (hoặc dùng PHP built-in server)
- **Git** (tùy chọn, để clone repository)

### Kiểm tra phiên bản:
```bash
php -v
mysql --version
```

---

## 🚀 Cài Đặt và Chạy

### **BƯỚC 1: Tải mã nguồn**

#### Cách 1: Clone từ GitHub
```bash
git clone https://github.com/nglum/Bai3_CaiDatMaNguon.git
cd Bai3_CaiDatMaNguon
```

#### Cách 2: Download ZIP
- Tải file ZIP từ GitHub
- Giải nén vào thư mục `htdocs` (nếu dùng XAMPP) hoặc thư mục web của bạn

---

### **BƯỚC 2: Cấu hình Database**

#### 2.1 Tạo Database
Mở terminal và kết nối MySQL:

```bash
mysql -u root -p
```

Nhập mật khẩu (nếu có, bỏ qua nếu không)

Chạy lệnh tạo database:
```sql
CREATE DATABASE IF NOT EXISTS cine_ticket_db;
USE cine_ticket_db;
```

#### 2.2 Import dữ liệu từ file SQL
Thoát MySQL và chạy:

```bash
# Nếu bạn có mật khẩu
mysql -u root -p cine_ticket_db < database.sql

# Nếu không có mật khẩu
mysql -u root cine_ticket_db < database.sql
```

**Hoặc** import trong phpMyAdmin:
1. Mở http://localhost/phpmyadmin
2. Tạo database `cine_ticket_db`
3. Tab **Import** → Chọn file `database.sql` → Click **Go**

#### 2.3 Kiểm tra dữ liệu
```bash
mysql -u root cine_ticket_db -e "SHOW TABLES; SELECT * FROM genres;"
```

Nếu thấy bảng và dữ liệu ✅ là thành công!

---

### **BƯỚC 3: Cấu hình file config**

Mở file `config/database.php` và kiểm tra:

```php
define('DB_HOST', 'localhost');    // Host MySQL (thường là localhost)
define('DB_USER', 'root');         // Username MySQL
define('DB_PASS', '');             // Password MySQL (để trống nếu không có)
define('DB_NAME', 'cine_ticket_db');
```

⚠️ **Nếu có mật khẩu MySQL, sửa `DB_PASS`:**
```php
define('DB_PASS', 'your_password'); // Thay your_password bằng mật khẩu thực
```

---

### **BƯỚC 4: Chạy ứng dụng**

#### Cách 1: Dùng PHP Built-in Server (Đơn giản nhất)

```bash
cd Bai3_CaiDatMaNguon
php -S localhost:8000
```

Sau đó truy cập: **http://localhost:8000**

#### Cách 2: Dùng XAMPP

1. **Copy thư mục vào htdocs:**
   ```bash
   cp -r Bai3_CaiDatMaNguon C:/xampp/htdocs/
   ```

2. **Khởi động XAMPP:**
   - Mở XAMPP Control Panel
   - Bấm **Start** cho Apache và MySQL

3. **Truy cập:**
   - Trang chủ: http://localhost/Bai3_CaiDatMaNguon/index.php
   - Admin: http://localhost/Bai3_CaiDatMaNguon/pages/admin/login.php

#### Cách 3: Dùng Nginx + PHP-FPM (Advanced)

```bash
# Cấu hình Nginx virtual host
server {
    listen 80;
    server_name cine-cinema.local;
    root /path/to/Bai3_CaiDatMaNguon;
    
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

---

### **BƯỚC 5: Kiểm tra cài đặt**

Truy cập: **http://localhost:8000/index.php**

✅ **Nếu bạn thấy:**
- Trang chủ với danh sách 5 phim
- Menu "Trang Chủ", "Đặt Vé", "Admin"
- Phim có thể tìm kiếm và lọc

→ **Cài đặt thành công!** 🎉

❌ **Nếu có lỗi:**
- Xem phần **Giải quyết lỗi** bên dưới

---

## 📁 Kiến Trúc Dự Án

```
Bai3_CaiDatMaNguon/
│
├── index.php                    # Trang chủ khách hàng
├── database.sql                 # Script tạo database
├── README.md                    # File này
│
├── config/
│   └── database.php             # Cấu hình kết nối MySQL
│
├── pages/
│   ├── booking.php              # Trang đặt vé (4 bước)
│   └── admin/
│       ├── login.php            # Trang đăng nhập admin
│       ├── dashboard.php        # Dashboard quản lý
│       ├── movies.php           # Quản lý phim
│       ├── showtimes.php        # Quản lý suất chiếu
│       ├── bookings.php         # Quản lý hóa đơn
│       ├── logout.php           # Đăng xuất
│       └── auth.php             # Xác thực đăng nhập
│
├── api/
│   ├── movies.php               # API phim
│   ├── genres.php               # API thể loại
│   ├── showtimes.php            # API suất chiếu
│   └── bookings.php             # API hóa đơn
│
├── helpers/
│   └── functions.php            # Hàm hỗ trợ chung
│
└── assets/
    ├── css/
    │   ├── style.css            # CSS trang chủ
    │   ├── booking.css          # CSS trang đặt vé
    │   ├── admin.css            # CSS admin
    │   └── responsive.css       # CSS responsive
    └── js/
        ├── main.js              # JS trang chủ
        ├── booking.js           # JS đặt vé
        ├── admin.js             # JS admin dashboard
        ├── admin-movies.js      # JS quản lý phim
        ├── admin-showtimes.js   # JS quản lý suất chiếu
        ├── admin-bookings.js    # JS quản lý hóa đơn
        └── admin-login.js       # JS đăng nhập
```

---

## 💾 Cơ Sở Dữ Liệu

### Bảng chính:

1. **genres** - Thể loại phim
2. **movies** - Danh sách phim
3. **showtimes** - Suất chiếu
4. **bookings** - Hóa đơn đặt vé
5. **booking_items** - Chi tiết vé (ghế ngồi)

### Schema:

```sql
-- Xem toàn bộ cấu trúc
DESCRIBE genres;
DESCRIBE movies;
DESCRIBE showtimes;
DESCRIBE bookings;
DESCRIBE booking_items;
```

---

## 📖 Hướng Dẫn Sử Dụng

### 👤 **Khách Hàng**

#### 1. Trang chủ (index.php)
- Xem danh sách phim đang chiếu
- Lọc theo thể loại
- Tìm kiếm theo tên phim
- Bấm vào phim để đặt vé

#### 2. Đặt vé (booking.php)
Quy trình 4 bước:

**Bước 1: Chọn Phim**
- Chọn phim từ danh sách
- Bấm "Tiếp tục"

**Bước 2: Chọn Suất & Ghế**
- Chọn ngày chiếu
- Chọn suất chiếu (khung giờ)
- Chọn ghế (ghế xanh trống, ghế xám đã đặt)
- Bấm "Tiếp tục"

**Bước 3: Nhập Thông Tin**
- Tên khách hàng
- Số điện thoại (10 số, bắt đầu từ 0)
- Email hợp lệ
- Bấm "Tiếp tục"

**Bước 4: Xác Nhận**
- Kiểm tra thông tin đơn hàng
- Xem tổng tiền
- Bấm "Xác Nhận Đặt Vé"

✅ Thành công! Bạn sẽ nhận mã hóa đơn

---

### 👨‍💼 **Admin**

#### Đăng Nhập
- URL: http://localhost:8000/pages/admin/login.php
- **Username:** `admin`
- **Password:** `admin123`

#### Dashboard (dashboard.php)
Xem thống kê:
- Tổng số phim
- Tổng số suất chiếu
- Tổng số hóa đơn
- Doanh thu (chỉ tính từ vé đã thanh toán)

#### Quản Lý Phim (movies.php)
**CRUD Phim:**
- ✅ **Thêm:** Bấm "+ Thêm Phim"
- ✅ **Sửa:** Bấm "Sửa" ở mỗi hàng
- ✅ **Xóa:** Bấm "Xóa" ở mỗi hàng

**Fields:**
- Tên phim (bắt buộc)
- Thể loại (bắt buộc)
- Thời lượng (phút, bắt buộc)
- Mô tả
- Trạng thái (Đang chiếu / Sắp chiếu / Kết thúc)

#### Quản Lý Suất Chiếu (showtimes.php)
**Xếp Lịch:**
- Bấm "+ Thêm Suất Chiếu"
- Chọn Phim
- Chọn Phòng (01-05)
- Chọn Ngày Chiếu
- Nhập Giờ Bắt Đầu
- Nhập Giá Vé

⚠️ **Kiểm tra trùng lịch:** Hệ thống tự động kiểm tra nếu phòng đó đã có phim khác vào cùng giờ

#### Quản Lý Hóa Đơn (bookings.php)
**Xem Hóa Đơn:**
- Lọc theo trạng thái
- Bấm "Chi tiết" để xem danh sách ghế

**Cập Nhật Trạng Thái:**
- Chờ thanh toán → Đã thanh toán → Đã hủy
- Bấm "Cập Nhật"

---

## 📡 API Documentation

### Base URL
```
http://localhost:8000/api/
```

### Endpoints

#### Movies API
```
GET    /movies.php?action=list              # Lấy danh sách phim
GET    /movies.php?action=get&id=1          # Lấy chi tiết phim
POST   /movies.php?action=create            # Tạo phim
POST   /movies.php?action=update            # Cập nhật phim
GET    /movies.php?action=delete&id=1       # Xóa phim
```

#### Genres API
```
GET    /genres.php?action=list              # Lấy danh sách thể loại
POST   /genres.php?action=create            # Tạo thể loại
POST   /genres.php?action=update            # Cập nhật thể loại
GET    /genres.php?action=delete&id=1       # Xóa thể loại
```

#### Showtimes API
```
GET    /showtimes.php?action=list           # Lấy danh sách suất chiếu
GET    /showtimes.php?action=by-movie&movie_id=1&show_date=2026-06-02
GET    /showtimes.php?action=by-date&show_date=2026-06-02
POST   /showtimes.php?action=create         # Tạo suất chiếu
POST   /showtimes.php?action=update         # Cập nhật suất chiếu
GET    /showtimes.php?action=delete&id=1    # Xóa suất chiếu
```

#### Bookings API
```
GET    /bookings.php?action=list            # Lấy danh sách hóa đơn
GET    /bookings.php?action=get&id=1        # Chi tiết hóa đơn
GET    /bookings.php?action=get-seats&showtime_id=1  # Lấy ghế đã đặt
POST   /bookings.php?action=create          # Tạo hóa đơn
POST   /bookings.php?action=update-status   # Cập nhật trạng thái
```

---

## 🐛 Giải Quyết Lỗi

### ❌ Lỗi: "Lỗi kết nối database"

**Nguyên nhân & Giải pháp:**

1. **MySQL không chạy**
   ```bash
   # Khởi động MySQL (Windows XAMPP)
   # Mở XAMPP Control Panel → Start MySQL
   
   # Hoặc Linux
   sudo service mysql start
   ```

2. **Thông tin đăng nhập sai**
   - Kiểm tra lại `config/database.php`
   - Username phải là `root` (hoặc tài khoản MySQL của bạn)
   - Password phải khớp

3. **Database chưa tạo**
   ```bash
   mysql -u root -p cine_ticket_db < database.sql
   ```

---

### ❌ Lỗi: "404 Not Found"

**Nguyên nhân & Giải pháp:**

1. **Đường dẫn sai**
   - Nếu dùng XAMPP: http://localhost/Bai3_CaiDatMaNguon/index.php
   - Nếu dùng PHP server: http://localhost:8000/index.php

2. **PHP server chưa chạy**
   ```bash
   cd Bai3_CaiDatMaNguon
   php -S localhost:8000
   ```

---

### ❌ Lỗi: "CSS/JS không load"

**Nguyên nhân & Giải pháp:**

1. **Đường dẫn asset sai**
   - Nếu dùng PHP server: các file CSS/JS sẽ tự động load từ `/assets/`
   - Kiểm tra trong browser (F12) tab **Network** xem file nào không load

2. **Quyền file không đủ (Linux/Mac)**
   ```bash
   chmod -R 755 Bai3_CaiDatMaNguon/assets/
   ```

---

### ❌ Lỗi: "Phim không hiển thị"

**Nguyên nhân & Giải pháp:**

1. **Dữ liệu chưa được import**
   ```bash
   mysql -u root cine_ticket_db < database.sql
   ```

2. **API không phản hồi**
   - Mở Console (F12) trong browser
   - Kiểm tra lỗi trong tab **Console** hoặc **Network**

---

### ❌ Lỗi: "Đăng nhập Admin không được"

**Nguyên nhân & Giải pháp:**

- Đăng nhập lần đầu có thể không thành công do session
- **Cách 1:** Làm mới trang (F5) rồi đăng nhập lại
- **Cách 2:** Xóa cache browser: Ctrl+Shift+Delete
- **Cách 3:** Dùng incognito/private window

---

### ❌ Lỗi: "UTF-8 ký tự Việt lỗi"

**Nguyên nhân & Giải pháp:**

Trong `config/database.php`, thêm dòng:
```php
$conn->set_charset('utf8mb4');
```

(Đã có sẵn trong code, nên không lo)

---

## ✅ Checklist Kiểm Tra

Khi cài đặt xong, kiểm tra các điểm sau:

- [ ] PHP phiên bản 7.0+
- [ ] MySQL chạy bình thường
- [ ] Database `cine_ticket_db` đã tạo
- [ ] Dữ liệu từ `database.sql` đã import
- [ ] File `config/database.php` đúng
- [ ] Truy cập http://localhost:8000 thành công
- [ ] Thấy 5 phim trong danh sách
- [ ] Có thể đặt vé
- [ ] Admin login được bằng admin/admin123
- [ ] Có thể thêm/sửa/xóa phim trong admin

---

## 🎓 Tính Năng Chính

### ✨ Khách Hàng
- ✅ Xem & lọc phim
- ✅ Tìm kiếm phim
- ✅ Đặt vé 4 bước
- ✅ Sơ đồ ghế động
- ✅ Kiểm tra ghế trùng (lock seat)
- ✅ Nhập thông tin khách hàng
- ✅ Tính toán tổng tiền

### ✨ Admin
- ✅ CRUD phim & thể loại
- ✅ Xếp lịch chiếu
- ✅ Kiểm tra trùng lịch phòng
- ✅ Quản lý hóa đơn
- ✅ Cập nhật trạng thái vé
- ✅ Xem chi tiết hóa đơn (danh sách ghế)
- ✅ Tính toán doanh thu
- ✅ Dashboard thống kê

### 🔒 Bảo Mật
- ✅ Xác thực admin
- ✅ Session handling
- ✅ Input validation
- ✅ Protection against SQL injection

---

## 📞 Hỗ Trợ & Liên Hệ

Nếu gặp vấn đề:

1. **Xem phần "Giải quyết lỗi"** ở trên
2. **Kiểm tra Console** (F12 trong browser)
3. **Kiểm tra error logs** của PHP/MySQL

---

## 📝 Tác Giả

- **Sinh viên:** @nglum
- **Môn học:** Công nghệ Phần mềm
- **Ngày tạo:** 2026-06-01
- **Repository:** https://github.com/nglum/Bai3_CaiDatMaNguon

---

## 📄 License

Dự án này được tạo cho mục đích giáo dục.

---

## 🚀 Bước Tiếp Theo (Cải Tiến Tương Lai)

- [ ] Thanh toán online (Stripe, PayPal)
- [ ] Email confirmation
- [ ] QR code vé
- [ ] Mobile app
- [ ] Analytics dashboard
- [ ] Khuyến mãi & discount
- [ ] Rating phim

---

**Chúc bạn sử dụng hệ thống thành công! 🎉**

Nếu có câu hỏi, vui lòng liên hệ hoặc tạo issue trên GitHub.
