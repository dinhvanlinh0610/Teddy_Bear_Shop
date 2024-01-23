<?php
// login.php

// Kết nối CSDL
include_once('connection/connection.php');

// Bắt đầu phiên làm việc
session_start();

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập
    $sql = "SELECT id, username FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];

        // Chuyển hướng đến trang chủ
        header("Location: index.php");
        exit();
    } else {
        $loginError = "Đăng nhập không thành công. Vui lòng kiểm tra lại thông tin đăng nhập.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Gấu Bông Shop</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Giỏ Hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Đăng Nhập</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="container mt-4">
        <h2>Đăng Nhập</h2>
        <form method="post" action="">
            <!-- Các trường đăng nhập -->
            <!-- ... (code đăng nhập) -->
            <form method="post" action="">
    <label for="username">Tên đăng nhập:</label>
    <input type="text" name="username" required>

    <label for="password">Mật khẩu:</label>
    <input type="password" name="password" required>

    <button type="submit">Đăng nhập</button>
</form>
            <button type="submit" name="login" class="btn btn-primary">Đăng Nhập</button>
        </form>
        <?php
        // Hiển thị thông báo lỗi đăng nhập (nếu có)
        if (isset($loginError)) {
            echo '<div class="alert alert-danger mt-3" role="alert">' . $loginError . '</div>';
        }
        ?>
    </section>

    <footer class="mt-5 text-center">
        <p>&copy; 2024 Gấu Bông Shop</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<!-- Form đăng nhập -->

