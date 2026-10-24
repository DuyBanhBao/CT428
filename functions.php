<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

function h($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}


function hien_dau_trang($tieude) {
    ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h($tieude); ?> - Đặt Lịch Khám</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="thanh-tren">
    <div class="rong">
        <span>Hotline: 363636</span>
        <span>Thứ 2 đến Thứ 7 | 07:30 đến 16:30</span>
    </div>
</div>

<header class="dau-trang">
    <div class="rong dau-trang-ben-trong">
        <a href="index.php" class="logo"> Đặt Lịch Khám Bệnh</a>
        <nav class="menu">
            <a href="index.php">Trang chủ</a>
            <a href="index.php#chuyen-khoa">Chuyên khoa</a>
            <a href="index.php#bac-si">Bác sĩ</a>
            <a href="dat_lich.php">Đặt lịch</a>
            <?php if (isset($_SESSION['id'])): ?>
                <a href="lich_cua_toi.php">Lịch khám của tôi</a>
            <?php endif; ?>
        </nav>
        <div class="tai-khoan">
            <?php hien_khu_tai_khoan(); ?>
        </div>
    </div>
</header>

<main>
    <?php
}

function hien_khu_tai_khoan() {
    ?>
    <?php if (isset($_SESSION['id'])): ?>
        <span>Xin chào, <strong><?php echo h($_SESSION['hoten']); ?></strong></span>
        <a href="dang_xuat.php" class="nut-nho nut-vien">Đăng xuất</a>
    <?php else: ?>
        <a href="dang_nhap.php" class="nut-nho nut-vien">Đăng nhập</a>
        <a href="dang_ky.php" class="nut-nho">Đăng ký</a>
    <?php endif; ?>
    <?php
}

function hien_cuoi_trang() {
    ?>
</main>

<footer class="chan-trang">
    <div class="rong">
        <p><strong>Hệ thống Đặt Lịch Khám Bệnh Trực Tuyến</strong></p>
        <p>Địa chỉ: 123 Đường 456, TP. Cần Thơ</p>
        <p>&copy; <?php echo date('D '); echo date('M '); echo date('Y'); ?> Đồ án môn CT428</p>
    </div>
</footer>

</body>
</html>
    <?php
}


function nhap($nhan, $ten, $kieu = 'text', $giatri = '', $batbuoc = true) {
    ?>
    <label for="<?php echo h($ten); ?>"><?php echo h($nhan); ?></label>
    <input
        type="<?php echo h($kieu); ?>"
        id="<?php echo h($ten); ?>"
        name="<?php echo h($ten); ?>"
        value="<?php echo h($giatri); ?>"
        <?php if ($batbuoc) { echo 'required'; } ?>
    >
    <?php
}

function hien_thong_bao($noidung, $loai) {
    if ($noidung != "") {
        echo '<p class="' . $loai . '">' . h($noidung) . '</p>';
    }
}

function tim_nguoi_dung($conn, $tendn) {
    $stmt = $conn->prepare("SELECT * FROM BenhNhan WHERE TenDangNhap=?");
    $stmt->bind_param("s", $tendn);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// in 1 the
function hien_the($bieutuong, $tieude, $mota = '') {
    ?>
    <div class="the">
        <div class="vong"><?php echo h($bieutuong); ?></div>
        <h3><?php echo h($tieude); ?></h3>
        <?php if ($mota != ""): ?>
            <p><?php echo h($mota); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

// lay ds chuyen khoa khong trung voi bac si
function lay_chuyen_khoa($conn) {
    $ketqua = $conn->query("SELECT DISTINCT ChuyenKhoa FROM BacSi ORDER BY ChuyenKhoa");
    return $ketqua->fetch_all(MYSQLI_ASSOC);
}

// lay ds bac si
function lay_bac_si($conn) {
    $ketqua = $conn->query("SELECT * FROM BacSi ORDER BY ID");
    return $ketqua->fetch_all(MYSQLI_ASSOC);
}

function bat_buoc_dang_nhap() {
    if (!isset($_SESSION['id'])) {
        header("Location: dang_nhap.php");
        exit;
    }
}

//  in một ô chọn danh sách thả xuống
function chon($nhan, $ten, $luachon, $giatri = '') {
    ?>
    <label for="<?php echo h($ten); ?>"><?php echo h($nhan); ?></label>
    <select id="<?php echo h($ten); ?>" name="<?php echo h($ten); ?>" required>
        <option value="">-- Chọn --</option>
        <?php foreach ($luachon as $ma => $chu): ?>
            <option value="<?php echo h($ma); ?>" <?php if ((string)$ma === (string)$giatri) { echo 'selected'; } ?>>
                <?php echo h($chu); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function trong_gio_lam_viec($gio) {
    if (date('N', $gio) == 7) {
        return false;                                 
    }
    $phut = (int)date('G', $gio) * 60 + (int)date('i', $gio);   // số phút tính từ 00:00
    return $phut >= 7 * 60 + 30 && $phut <= 16 * 60 + 30;
}

function bac_si_ban($conn, $id_bacsi, $thoigian) {
    $stmt = $conn->prepare("SELECT ID FROM LichKham WHERE ID_BacSi=? AND ThoiGian=? AND TrangThai NOT IN ('Đã hủy', 'Từ chối')");
    $stmt->bind_param("is", $id_bacsi, $thoigian);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ? true : false;
}

function luu_lich_kham($conn, $id_benhnhan, $id_bacsi, $thoigian, $lydo) {
    $stmt = $conn->prepare("INSERT INTO LichKham (ID_BenhNhan, ID_BacSi, ThoiGian, LyDo) VALUES (?,?,?,?)");
    $stmt->bind_param("iiss", $id_benhnhan, $id_bacsi, $thoigian, $lydo);
    $stmt->execute();
}

function lay_lich_cua_toi($conn, $id_benhnhan) {
    $stmt = $conn->prepare(
        "SELECT l.ID, l.ThoiGian, l.LyDo, l.TrangThai, b.HoTen AS TenBacSi, b.ChuyenKhoa
         FROM LichKham l
         JOIN BacSi b ON l.ID_BacSi = b.ID
         WHERE l.ID_BenhNhan=?
         ORDER BY l.ThoiGian DESC"
    );
    $stmt->bind_param("i", $id_benhnhan);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// huy duoc = true, khong duoc = false, chi huy duoc lich cua minh va cho xac nhan
function huy_lich($conn, $id_lich, $id_benhnhan) {
    $stmt = $conn->prepare("UPDATE LichKham SET TrangThai='Đã hủy' WHERE ID=? AND ID_BenhNhan=? AND TrangThai='Chờ xác nhận'");
    $stmt->bind_param("ii", $id_lich, $id_benhnhan);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}

function lop_trang_thai($trangthai) {
    if ($trangthai == 'Chờ xác nhận') {
        return 'tt-cho';
    }
    if ($trangthai == 'Đã xác nhận') {
        return 'tt-xacnhan';
    }
    return 'tt-huy';
}