<?php
session_start();


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