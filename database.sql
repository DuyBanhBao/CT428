CREATE DATABASE IF NOT EXISTS datlichkham CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE datlichkham;

-- Bảng bệnh nhân (thêm TenDangNhap, MatKhau, VaiTro để đăng nhập)
CREATE TABLE BenhNhan (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  HoTen VARCHAR(100) NOT NULL,
  NgaySinh DATE,
  SDT VARCHAR(15),
  TenDangNhap VARCHAR(50) UNIQUE,
  MatKhau VARCHAR(255),
  VaiTro VARCHAR(10) NOT NULL DEFAULT 'benhnhan'   -- 'benhnhan' hoặc 'admin'
);

CREATE TABLE BacSi (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  HoTen VARCHAR(100) NOT NULL,
  ChuyenKhoa VARCHAR(100) NOT NULL
);

CREATE TABLE LichKham (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  ID_BenhNhan INT NOT NULL,
  ID_BacSi INT NOT NULL,
  ThoiGian DATETIME NOT NULL,
  LyDo VARCHAR(255),
  TrangThai VARCHAR(20) NOT NULL DEFAULT 'Chờ xác nhận',
  FOREIGN KEY (ID_BenhNhan) REFERENCES BenhNhan(ID) ON DELETE CASCADE,
  FOREIGN KEY (ID_BacSi) REFERENCES BacSi(ID) ON DELETE CASCADE
);

INSERT INTO BacSi (HoTen, ChuyenKhoa) VALUES
('BS. Nguyễn Văn An', 'Nội tổng quát'),
('BS. Trần Thị Bình', 'Nhi khoa'),
('BS. Lê Minh Châu', 'Tim mạch'),
('BS. Phạm Quốc Dũng', 'Da liễu'),
('BS. Võ Thị Hoa', 'Tai Mũi Họng');
