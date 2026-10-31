<?php
// Các câu SQL dành riêng cho phần quản trị viên

// Đếm nhanh các con số cho trang tổng quan, chỉ cần MỘT câu truy vấn
// (mỗi dấu ngoặc con là một phép đếm riêng, kết quả là một dòng duy nhất)
function thong_ke_tong_quan($conn) {
    $ketqua = $conn->query(
        "SELECT
            (SELECT COUNT(*) FROM BENH_NHAN) AS benh_nhan,
            (SELECT COUNT(*) FROM BAC_SI) AS bac_si,
            (SELECT COUNT(*) FROM LICH_KHAM WHERE trangthai = 'cho_xac_nhan') AS lich_cho,
            (SELECT COUNT(*) FROM NGUOI_DUNG WHERE trangthai = 'bi_khoa') AS tai_khoan_khoa"
    );
    return $ketqua->fetch_assoc();
}