<?php
require_once 'connect.php';
require_once 'functions.php';

// dang nhap roi khong dang ky nua
if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$loi = "";
$hoten = "";
$ngaysinh = "";
$sdt = "";
$tendn = "";

// xu ly dang ky
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hoten = trim($_POST['hoten'] ?? '');
    $ngaysinh = $_POST['ngaysinh'] ?? '';
    $sdt = trim($_POST['sdt'] ?? '');
    $tendn = trim($_POST['tendn'] ?? '');
    $matkhau = $_POST['matkhau'] ?? '';
    $nhaplai = $_POST['nhaplai'] ?? '';

    if ($hoten == "" || $ngaysinh == "" || $tendn == "") {
        $loi = "Vui lòng nhập đủ thông tin bắt buộc.";
    } elseif ($sdt != "" && !ctype_digit($sdt)) {
        $loi = "Số điện thoại chỉ được gồm chữ số.";
    } elseif (strlen($matkhau) < 6) {
        $loi = "Mật khẩu phải từ 6 ký tự trở lên.";
    } elseif ($matkhau != $nhaplai) {
        $loi = "Mật khẩu nhập lại không khớp.";
    } elseif (tim_nguoi_dung($conn, $tendn)) {
        $loi = "Tên đăng nhập đã tồn tại, vui lòng chọn tên khác.";
    } else {
        
        $mk = password_hash($matkhau, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO BenhNhan (HoTen, NgaySinh, SDT, TenDangNhap, MatKhau) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $hoten, $ngaysinh, $sdt, $tendn, $mk);
        $stmt->execute();

        header("Location: dang_nhap.php?dk=ok");
        exit;
    }
}

hien_dau_trang('Đăng ký');
?>
<div class="khung">
<h2>Đăng ký tài khoản</h2>

<?php hien_thong_bao($loi, 'loi'); ?>

<form method="post">
    <?php
    nhap('Họ tên *', 'hoten', 'text', $hoten);
    nhap('Ngày sinh *', 'ngaysinh', 'date', $ngaysinh);
    nhap('Số điện thoại', 'sdt', 'text', $sdt, false);
    nhap('Tên đăng nhập *', 'tendn', 'text', $tendn);
    nhap('Mật khẩu *', 'matkhau', 'password');
    nhap('Nhập lại mật khẩu *', 'nhaplai', 'password');
    ?>
    <button type="submit" class="nut nut-luc">Đăng ký</button>
</form>

<a class="lien-ket" href="dang_nhap.php">Đã có tài khoản? Đăng nhập ngay</a>
</div>
<?php
hien_cuoi_trang();