<?php
require_once 'connect.php';
require_once 'functions.php';

bat_buoc_dang_nhap();

$loi = "";
$thoigian = "";
$lydo = "";
$bacsi_chon = $_GET['bacsi'] ?? '';

// doi ds bacsi => id - ten
$luachon = [];
foreach (lay_bac_si($conn) as $bs) {
    $luachon[$bs['ID']] = $bs['HoTen'] . ' - ' . $bs['ChuyenKhoa'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bacsi_chon = $_POST['bacsi'] ?? '';
    $thoigian = $_POST['thoigian'] ?? '';
    $lydo = trim($_POST['lydo'] ?? '');

    // doi chuoi ngay gio thanh 1 so, sai dinh dang = false
    $gio = strtotime($thoigian);

    if (!isset($luachon[$bacsi_chon])) {
        $loi = "Vui lòng chọn bác sĩ.";
    } elseif ($gio === false) {
        $loi = "Ngày giờ khám không hợp lệ.";
    } elseif ($gio <= time()) {
        $loi = "Ngày giờ khám phải ở tương lai.";
    } elseif (!trong_gio_lam_viec($gio)) {
        $loi = "Phòng khám làm việc từ thứ 2 đến thứ 7, 07:30 - 16:30.";
    } elseif (mb_strlen($lydo) > 255) {
        $loi = "Lý do khám tối đa 255 ký tự.";
    } elseif (bac_si_ban($conn, $bacsi_chon, date('Y-m-d H:i:s', $gio))) {
        $loi = "Bác sĩ đã có lịch vào giờ này, vui lòng chọn giờ khác.";
    } else {
        luu_lich_kham($conn, $_SESSION['id'], $bacsi_chon, date('Y-m-d H:i:s', $gio), $lydo);
        header("Location: lich_cua_toi.php?dat=ok");
        exit;
    }
}

hien_dau_trang('Đặt lịch khám');
?>
<div class="khung">
    <h2>Đặt lịch khám</h2>

    <?php hien_thong_bao($loi, 'loi'); ?>

    <form method="post">
        <?php
        chon('Bác sĩ *', 'bacsi', $luachon, $bacsi_chon);
        nhap('Ngày giờ khám *', 'thoigian', 'datetime-local', $thoigian);
        nhap('Lý do khám', 'lydo', 'text', $lydo, false);
        ?>
        <button type="submit" class="nut nut-luc">Xác nhận đặt lịch</button>
    </form>

    <a class="lien-ket" href="lich_cua_toi.php">Xem lịch khám của tôi</a>
</div>
<?php
hien_cuoi_trang();