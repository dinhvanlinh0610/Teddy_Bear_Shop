<?php
include_once('connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash mật khẩu trước khi lưu vào CSDL
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Đăng ký thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!-- Form đăng ký -->
<form method="post" action="">
    <label for="username">Tên đăng nhập:</label>
    <input type="text" name="username" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="password">Mật khẩu:</label>
    <input type="password" name="password" required>

    <button type="submit">Đăng ký</button>
</form>
