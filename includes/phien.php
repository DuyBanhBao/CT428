<?php
// Phiên làm việc (session), múi giờ, chuyển trang, kiểm tra đăng nhập
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Chuyển sang trang khác rồi dừng chương trình
function chuyen_trang($duongdan) {
    header("Location: " . $duongdan);
    exit;
}

// Trang nào gọi hàm này thì bắt buộc phải đăng nhập.
// $goc là phần đường dẫn đi về thư mục gốc: file ở gốc để trống,
// file trong thư mục xuly/ thì truyền '../'
function bat_buoc_dang_nhap($goc = '') {
    if (!isset($_SESSION['id'])) {
        chuyen_trang($goc . 'dang_nhap.php');
    }
}

// Chỉ bệnh nhân mới được vào
function bat_buoc_benh_nhan($goc = '') {
    bat_buoc_dang_nhap($goc);
    if ($_SESSION['vaitro'] != 'benh_nhan') {
        chuyen_trang($goc . 'index.php');
    }
}

// Ghi một lời nhắn để hiện ở trang kế tiếp. $loai là 'loi' hoặc 'ok'
function dat_thong_bao($loai, $noidung) {
    $_SESSION['thong_bao'] = ['loai' => $loai, 'noidung' => $noidung];
}

// Nhớ lại dữ liệu người dùng đã gõ để điền lại vào form.
// Mật khẩu KHÔNG BAO GIỜ được lưu lại.
function luu_du_lieu_cu($mang) {
    unset($mang['matkhau'], $mang['nhaplai']);
    $_SESSION['du_lieu_cu'] = $mang;
}

// Lấy dữ liệu đã nhớ ra dùng, rồi xóa luôn để không dùng lại lần sau
function lay_du_lieu_cu() {
    $cu = $_SESSION['du_lieu_cu'] ?? [];
    unset($_SESSION['du_lieu_cu']);
    return $cu;
}