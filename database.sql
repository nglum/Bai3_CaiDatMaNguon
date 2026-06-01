-- ===================================
-- DATABASE SCHEMA FOR CINE TICKET BOOKING SYSTEM
-- ===================================

-- Tạo Database
CREATE DATABASE IF NOT EXISTS cine_ticket_db;
USE cine_ticket_db;

-- ===================================
-- TABLE: GENRES (Thể loại phim)
-- ===================================
CREATE TABLE genres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===================================
-- TABLE: MOVIES (Phim)
-- ===================================
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    duration INT NOT NULL COMMENT 'Thời lượng phim (phút)',
    description LONGTEXT,
    image_url VARCHAR(500),
    genre_id INT NOT NULL,
    status ENUM('Đang chiếu', 'Sắp chiếu', 'Kết thúc') DEFAULT 'Đang chiếu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ===================================
-- TABLE: SHOWTIMES (Suất chiếu)
-- ===================================
CREATE TABLE showtimes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    movie_id INT NOT NULL,
    room_name VARCHAR(50) NOT NULL COMMENT 'Phòng 01, 02, 03...',
    show_date DATE NOT NULL,
    start_time TIME NOT NULL,
    ticket_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    UNIQUE KEY unique_showtime (room_name, show_date, start_time),
    INDEX idx_movie_date (movie_id, show_date),
    INDEX idx_room_date (room_name, show_date)
) ENGINE=InnoDB;

-- ===================================
-- TABLE: BOOKINGS (Hóa đơn đặt vé)
-- ===================================
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('Chờ thanh toán', 'Đã thanh toán', 'Đã hủy') DEFAULT 'Chờ thanh toán',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_booking_date (booking_date)
) ENGINE=InnoDB;

-- ===================================
-- TABLE: BOOKING_ITEMS (Chi tiết vé - Mối quan hệ nhiều-nhiều)
-- ===================================
CREATE TABLE booking_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    showtime_id INT NOT NULL,
    seat_number VARCHAR(10) NOT NULL COMMENT 'A1, A2, B5...',
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_seat_per_showtime (showtime_id, seat_number),
    INDEX idx_booking (booking_id),
    INDEX idx_showtime (showtime_id)
) ENGINE=InnoDB;

-- ===================================
-- INSERTING SAMPLE DATA
-- ===================================

-- Thêm thể loại
INSERT INTO genres (name) VALUES 
('Hành động'), 
('Tình cảm'), 
('Kinh dị'), 
('Hài hước'), 
('Khoa học viễn tưởng');

-- Thêm phim
INSERT INTO movies (title, duration, description, image_url, genre_id, status) VALUES 
('Avengers: Endgame', 181, 'Những anh hùng cuối cùng phải đối mặt với thế lực vô tận', '/assets/images/avengers.jpg', 1, 'Đang chiếu'),
('Titanic', 194, 'Tình yêu giữa đại dương xanh', '/assets/images/titanic.jpg', 2, 'Đang chiếu'),
('The Ring', 115, 'Những cơn ác mộng từ trong video', '/assets/images/the_ring.jpg', 3, 'Đang chiếu'),
('Trạng Quỳnh', 109, 'Hài kịch lịch sử Việt Nam', '/assets/images/trang_quynh.jpg', 4, 'Đang chiếu'),
('Inception', 148, 'Thế giới của những giấc mơ', '/assets/images/inception.jpg', 5, 'Sắp chiếu');

-- Thêm suất chiếu
INSERT INTO showtimes (movie_id, room_name, show_date, start_time, ticket_price) VALUES 
(1, 'Phòng 01', '2026-06-02', '14:00:00', 120000),
(1, 'Phòng 01', '2026-06-02', '17:00:00', 120000),
(1, 'Phòng 02', '2026-06-02', '19:00:00', 150000),
(2, 'Phòng 01', '2026-06-03', '16:00:00', 120000),
(3, 'Phòng 03', '2026-06-02', '20:00:00', 120000),
(4, 'Phòng 02', '2026-06-02', '10:00:00', 100000),
(5, 'Phòng 01', '2026-06-04', '18:00:00', 120000);

-- Thêm dữ liệu hóa đơn mẫu
INSERT INTO bookings (customer_name, phone, email, total_price, status) VALUES 
('Nguyễn Văn A', '0901234567', 'nguvana@email.com', 240000, 'Đã thanh toán'),
('Trần Thị B', '0912345678', 'tranthib@email.com', 150000, 'Chờ thanh toán');

-- Thêm chi tiết vé
INSERT INTO booking_items (booking_id, showtime_id, seat_number, price) VALUES 
(1, 1, 'A1', 120000),
(1, 1, 'A2', 120000),
(2, 3, 'B5', 150000);

-- ===================================
-- VERIFY TABLES
-- ===================================
SHOW TABLES;
SELECT COUNT(*) as total_genres FROM genres;
SELECT COUNT(*) as total_movies FROM movies;
SELECT COUNT(*) as total_showtimes FROM showtimes;
