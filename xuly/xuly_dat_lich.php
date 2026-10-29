<?php
require_once __DIR__ . '/../includes/khoitao.php';

bat_buoc_benh_nhan('../');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    chuyen_trang('../dat_lich.php');
}

$id_bacsi = (int)($_POST['bacsi'] ?? 0);
$id_gio = (int)($_POST['giokham'] ?? 0);
$lydo = trim($_POST['lydo'] ?? '');

// Khi lỗi thì quay về form, vẫn giữ bác sĩ đang chọn
$ve_form = '../dat_lich.php?bacsi=' . $id_bacsi;

// Kiểm tra 1: khung giờ có đúng của bác sĩ này, còn trống và ở tương lai không
if (!khung_gio_con_trong($conn, $id_bacsi, $id_gio)) {
    dat_thong_bao('loi', 'Vui lòng chọn khung giờ còn trống.');
    chuyen_trang($ve_form);
}

// Kiểm tra 2: độ dài lý do khám
if (mb_strlen($lydo) > 255) {
    luu_du_lieu_cu($_POST);
    dat_thong_bao('loi', 'Lý do khám tối đa 255 ký tự.');
    chuyen_trang($ve_form);
}

// Lưu. Thất bại nghĩa là khung giờ vừa bị người khác đặt mất (DB không cho đặt trùng)
if (!luu_lich_kham($conn, $_SESSION['id'], $id_gio, $lydo)) {
    dat_thong_bao('loi', 'Khung giờ này vừa có người khác đặt, vui lòng chọn giờ khác.');
    chuyen_trang($ve_form);
}

dat_thong_bao('ok', 'Đặt lịch thành công! Lịch của bạn đang chờ xác nhận.');
chuyen_trang('../lich_cua_toi.php');