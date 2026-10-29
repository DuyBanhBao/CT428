<?php
require_once 'includes/khoitao.php';

bat_buoc_benh_nhan();

$dsLich = lay_lich_cua_toi($conn, $_SESSION['id']);

hien_dau_trang('Lịch khám của tôi');
?>
<section class="khu rong">
    <h2 class="tieu-de-khu">Lịch khám của tôi</h2>

    <?php hien_thong_bao_phien(); ?>

    <?php if (count($dsLich) == 0): ?>
        <p class="trong">Bạn chưa có lịch khám nào. <a href="dat_lich.php">Đặt lịch ngay</a></p>
    <?php else: ?>
        <div class="bang-cuon">
            <table class="bang">
                <tr>
                    <th>Thời gian</th>
                    <th>Bác sĩ</th>
                    <th>Chuyên khoa</th>
                    <th>Lý do</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
                <?php foreach ($dsLich as $l): ?>
                    <tr>
                        <td><?php echo h(date('d/m/Y H:i', strtotime($l['ThoiGian']))); ?></td>
                        <td><?php echo h($l['TenBacSi']); ?></td>
                        <td><?php echo h($l['ChuyenKhoa']); ?></td>
                        <td><?php echo h($l['LyDo']); ?></td>
                        <td>
                            <span class="trangthai <?php echo lop_trang_thai($l['TrangThai']); ?>">
                                <?php echo h(ten_trang_thai($l['TrangThai'])); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($l['TrangThai'] == 'cho_xac_nhan'): ?>
                                <form method="post" action="xuly/xuly_huy_lich.php"
                                      onsubmit="return confirm('Bạn chắc chắn muốn hủy lịch này?');">
                                    <button type="submit" name="huy" value="<?php echo h($l['ID']); ?>" class="nut-huy">Hủy</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>

    <p class="trong"><a href="dat_lich.php">+ Đặt thêm lịch khám</a></p>
</section>
<?php
hien_cuoi_trang();