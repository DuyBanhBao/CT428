<?php
require_once __DIR__ . '/../includes/khoitao.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    chuyen_trang('../dang_nhap.php');
}

$sdt = trim($_POST['sdt'] ?? '');
$matkhau = $_POST['matkhau'] ?? '';

$user = tim_nguoi_dung($conn, $sdt);

// Sai SĐT hoặc sai mật khẩu: báo chung một câu, không nói rõ cái nào sai
if (!$user || !password_verify($matkhau, $user['MatKhau'])) {
    luu_du_lieu_cu(['sdt' => $sdt]);
    dat_thong_bao('loi', 'Sai số điện thoại hoặc mật khẩu.');
    chuyen_trang('../dang_nhap.php');
}

if ($user['TrangThai'] == 'bi_khoa') {
    luu_du_lieu_cu(['sdt' => $sdt]);
    dat_thong_bao('loi', 'Tài khoản của bạn đã bị khóa.');
    chuyen_trang('../dang_nhap.php');
}

// Đăng nhập thành công: lưu thông tin vào session
session_regenerate_id(true);
$_SESSION['id'] = $user['ID'];
$_SESSION['hoten'] = $user['HoTen'];
$_SESSION['vaitro'] = $user['VaiTro'];   // 'benh_nhan', 'bac_si' hoặc 'quan_tri'

chuyen_trang('../index.php');