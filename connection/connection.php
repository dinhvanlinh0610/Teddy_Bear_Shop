<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_2"; // Thay 'ten_csdl' bằng tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}


?>