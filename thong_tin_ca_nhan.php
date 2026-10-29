<?php
require_once 'includes/khoitao.php';

bat_buoc_dang_nhap();

// Lấy thông tin từ DB. Nếu lần trước nhập lỗi thì dữ liệu đã gõ được ghi đè lên
// (array_merge: giá trị ở mảng sau thắng khi trùng khóa)
$tt = array_merge(lay_thong_tin_ca_nhan($conn, $_SESSION['id']), lay_du_lieu_cu());

hien_dau_trang('Thông tin cá nhân');
?>
<div class="khung">
    <h2>Thông tin cá nhân</h2>

    <?php hien_thong_bao_phien(); ?>

    <form method="post" action="xuly/xuly_cap_nhat_thong_tin.php">
        <label>Số điện thoại (không đổi được)</label>
        <input value="<?php echo h($tt['sdt']); ?>" disabled>

        <?php
        nhap('Họ tên *', 'hoten', 'text', $tt['hoten']);
        nhap('Ngày sinh', 'ngaysinh', 'date', $tt['ngaysinh'], false);
        chon('Giới tính', 'gioitinh', ['nam' => 'Nam', 'nu' => 'Nữ'], $tt['gioitinh'], false);
        nhap('Email', 'email', 'email', $tt['email'], false);
        nhap('Địa chỉ', 'diachi', 'text', $tt['diachi'], false);
        nhap('Số CCCD (12 chữ số)', 'cccd', 'text', $tt['cccd'], false);
        nhap('Dân tộc', 'dantoc', 'text', $tt['dantoc'], false);
        nhap('Mã thẻ BHYT', 'bhyt', 'text', $tt['bhyt'], false);
        ?>
        <button type="submit" class="nut nut-luc">Lưu thông tin</button>
    </form>
</div>

<div class="khung">
    <h2>Đổi mật khẩu</h2>

    <form method="post" action="xuly/xuly_doi_mat_khau.php">
        <?php
        nhap('Mật khẩu hiện tại', 'matkhau_cu', 'password');
        nhap('Mật khẩu mới', 'matkhau_moi', 'password');
        nhap('Nhập lại mật khẩu mới', 'nhaplai', 'password');
        ?>
        <button type="submit" class="nut">Đổi mật khẩu</button>
    </form>
</div>
<?php
hien_cuoi_trang();