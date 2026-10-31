<?php
// Mọi trang và mọi file xử lý chỉ cần nạp một file này.
// __DIR__ là thư mục chứa file đang chạy (ở đây là includes/),
// nhờ vậy đường dẫn đúng dù file gọi nằm ở thư mục nào.
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/phien.php';
require_once __DIR__ . '/giao_dien.php';
require_once __DIR__ . '/truy_van.php';
require_once __DIR__ . '/truy_van_quan_tri.php';