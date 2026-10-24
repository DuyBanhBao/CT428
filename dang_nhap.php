<?php
require_once 'connect.php';
require_once 'functions.php';

// dang nhap roi thi back home
if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$loi = "";
$thanhcong = "";
$tendn = "";


if (isset($_GET['dk'])) {
    $thanhcong = "Đăng ký thành công, hãy đăng nhập.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tendn = trim($_POST['tendn'] ?? '');
    $matkhau = $_POST['matkhau'] ?? '';

    $user = tim_nguoi_dung($conn, $tendn);

    // so mat khau voi db
    if ($user && password_verify($matkhau, $user['MatKhau'])) {
        session_regenerate_id(true);   // doi ma phien
        $_SESSION['id'] = $user['ID'];
        $_SESSION['hoten'] = $user['HoTen'];
        $_SESSION['vaitro'] = $user['VaiTro'];

        header("Location: index.php");
        exit;
    }
    $loi = "Sai tên đăng nhập hoặc mật khẩu.";
}

hien_dau_trang('Đăng nhập');
?>
<div class="khung">
<h2>Đăng nhập</h2>

<?php
hien_thong_bao($thanhcong, 'ok');
hien_thong_bao($loi, 'loi');
?>

<form method="post">
    <?php
    nhap('Tên đăng nhập', 'tendn', 'text', $tendn);
    nhap('Mật khẩu', 'matkhau', 'password');
    ?>
    <button type="submit" class="nut">Đăng nhập</button>
</form>

<a class="lien-ket" href="dang_ky.php">Chưa có tài khoản? Đăng ký ngay</a>
<a class="lien-ket" href="index.php">Quay lại trang chủ</a>
</div>
<?php
hien_cuoi_trang();