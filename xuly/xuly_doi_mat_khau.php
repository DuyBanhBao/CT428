<?php
require_once __DIR__ . '/../includes/khoitao.php';

bat_buoc_dang_nhap('../');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    chuyen_trang('../thong_tin_ca_nhan.php');
}

$matkhau_cu = $_POST['matkhau_cu'] ?? '';
$matkhau_moi = $_POST['matkhau_moi'] ?? '';
$nhaplai = $_POST['nhaplai'] ?? '';

$loi = "";
if (!password_verify($matkhau_cu, lay_mat_khau($conn, $_SESSION['id']))) {
    $loi = "Mật khẩu hiện tại không đúng.";
} elseif (strlen($matkhau_moi) < 6) {
    $loi = "Mật khẩu mới phải từ 6 ký tự trở lên.";
} elseif ($matkhau_moi != $nhaplai) {
    $loi = "Mật khẩu nhập lại không khớp.";
} elseif ($matkhau_moi == $matkhau_cu) {
    $loi = "Mật khẩu mới phải khác mật khẩu hiện tại.";
}

if ($loi != "") {
    dat_thong_bao('loi', $loi);
    chuyen_trang('../thong_tin_ca_nhan.php');
}

doi_mat_khau($conn, $_SESSION['id'], password_hash($matkhau_moi, PASSWORD_DEFAULT));

dat_thong_bao('ok', 'Đã đổi mật khẩu.');
chuyen_trang('../thong_tin_ca_nhan.php');