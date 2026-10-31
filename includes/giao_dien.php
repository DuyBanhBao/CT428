<?php
//session_start();
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
            <?php hien_menu(); ?>
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
        <span>Xin chào, <a href="thong_tin_ca_nhan.php"><strong><?php echo h($_SESSION['hoten']); ?></strong></a></span>
        <a href="xuly/xuly_dang_xuat.php" class="nut-nho nut-vien">Đăng xuất</a>
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

//  in một ô chọn danh sách thả xuống
function chon($nhan, $ten, $luachon, $giatri = '', $batbuoc = true) {
    ?>
    <label for="<?php echo h($ten); ?>"><?php echo h($nhan); ?></label>
    <select id="<?php echo h($ten); ?>" name="<?php echo h($ten); ?>" <?php if ($batbuoc) { echo 'required'; } ?>>
        <option value="">-- Chọn --</option>
        <?php foreach ($luachon as $ma => $chu): ?>
            <option value="<?php echo h($ma); ?>" <?php if ((string)$ma === (string)$giatri) { echo 'selected'; } ?>>
                <?php echo h($chu); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function hien_thong_bao($noidung, $loai) {
    if ($noidung != "") {
        echo '<p class="' . $loai . '">' . h($noidung) . '</p>';
    }
}

function hien_the($bieutuong, $tieude, $mota = '', $lienket = '', $chu_lienket = '') {
    ?>
    <div class="the">
        <div class="vong"><?php echo h($bieutuong); ?></div>
        <h3><?php echo h($tieude); ?></h3>
        <?php if ($mota != ""): ?>
            <p><?php echo h($mota); ?></p>
        <?php endif; ?>
        <?php if ($lienket != ""): ?>
            <br>
            <a href="<?php echo h($lienket); ?>" class="nut-nho"><?php echo h($chu_lienket); ?></a>
        <?php endif; ?>
    </div>
    <?php
}

// Đổi mã trạng thái sang chữ dễ đọc
function ten_trang_thai($trangthai) {
    $ten = [
        'cho_xac_nhan' => 'Chờ xác nhận',
        'da_xac_nhan'  => 'Đã xác nhận',
        'da_kham'      => 'Đã khám',
        'da_huy'       => 'Đã hủy',
    ];
    return $ten[$trangthai] ?? $trangthai;
}

// Đổi trạng thái sang tên lớp CSS để tô màu
function lop_trang_thai($trangthai) {
    if ($trangthai == 'cho_xac_nhan') {
        return 'tt-cho';
    }
    if ($trangthai == 'da_xac_nhan' || $trangthai == 'da_kham') {
        return 'tt-xacnhan';
    }
    return 'tt-huy';
}

// In lời nhắn đã lưu trong session (nếu có), rồi xóa để chỉ hiện một lần
function hien_thong_bao_phien() {
    if (isset($_SESSION['thong_bao'])) {
        hien_thong_bao($_SESSION['thong_bao']['noidung'], $_SESSION['thong_bao']['loai']);
        unset($_SESSION['thong_bao']);
    }
}


// In một ô ẩn: người dùng không thấy, nhưng vẫn được gửi đi cùng form
function o_an($ten, $giatri) {
    ?>
    <input type="hidden" name="<?php echo h($ten); ?>" value="<?php echo h($giatri); ?>">
    <?php
}

// In các mục menu, đổi theo vai trò của người đang đăng nhập
function hien_menu() {
    $vaitro = $_SESSION['vaitro'] ?? '';   // '' nghĩa là khách chưa đăng nhập
    ?>
    <?php if ($vaitro == 'quan_tri'): ?>
        <a href="qt_trang_chu.php">Tổng quan</a>
        <a href="qt_nguoi_dung.php">Người dùng</a>
        <a href="qt_bac_si.php">Quản lý bác sĩ</a>
    <?php else: ?>
        <a href="index.php">Trang chủ</a>
        <a href="index.php#chuyen-khoa">Chuyên khoa</a>
        <a href="bac_si.php">Bác sĩ</a>
        <?php if ($vaitro == '' || $vaitro == 'benh_nhan'): ?>
            <a href="dat_lich.php">Đặt lịch</a>
        <?php endif; ?>
        <?php if ($vaitro == 'benh_nhan'): ?>
            <a href="lich_cua_toi.php">Lịch khám của tôi</a>
        <?php endif; ?>
    <?php endif; ?>
    <?php
}

// In một ô nhập nhiều dòng
function o_nhieu_dong($nhan, $ten, $giatri = '') {
    ?>
    <label for="<?php echo h($ten); ?>"><?php echo h($nhan); ?></label>
    <textarea id="<?php echo h($ten); ?>" name="<?php echo h($ten); ?>" rows="4"><?php echo h($giatri); ?></textarea>
    <?php
}

// In một nhóm ô tích chọn. $luachon dạng [mã => tên], $da_chon là mảng các mã đang được tích
function o_tich_chon($nhan, $ten, $luachon, $da_chon = []) {
    ?>
    <label><?php echo h($nhan); ?></label>
    <div class="nhom-tich">
        <?php foreach ($luachon as $ma => $chu): ?>
            <label class="o-tich">
                <input type="checkbox" name="<?php echo h($ten); ?>[]" value="<?php echo h($ma); ?>"
                    <?php if (in_array($ma, $da_chon)) { echo 'checked'; } ?>>
                <?php echo h($chu); ?>
            </label>
        <?php endforeach; ?>
    </div>
    <?php
}