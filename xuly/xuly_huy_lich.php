<?php
require_once __DIR__ . '/../includes/khoitao.php';

bat_buoc_benh_nhan('../');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    chuyen_trang('../lich_cua_toi.php');
}

$id_lich = (int)($_POST['huy'] ?? 0);

// huy_lich chỉ hủy được lịch CỦA CHÍNH NGƯỜI ĐANG ĐĂNG NHẬP và còn "chờ xác nhận"
if (huy_lich($conn, $id_lich, $_SESSION['id'])) {
    dat_thong_bao('ok', 'Đã hủy lịch khám.');
} else {
    dat_thong_bao('loi', 'Không thể hủy lịch này.');
}

chuyen_trang('../lich_cua_toi.php');