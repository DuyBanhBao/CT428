<?php
$conn = new mysqli("localhost", "root", "", "datlichkham");
if ($conn->connect_error) {
    die("Không kết nối được database: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");