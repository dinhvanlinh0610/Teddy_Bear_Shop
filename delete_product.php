<?php
include_once('connection/connection.php');
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra vai trò của người dùng
if ($_SESSION['role'] !== 'admin') {
    // Nếu không phải admin, chuyển hướng về trang không có quyền truy cập
    header("Location: unauthorized.php");
    exit();
}

// Lấy ID sản phẩm từ tham số truyền vào
if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = $_GET['id'];

// Kiểm tra xem sản phẩm có tồn tại không
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: manage_products.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Thực hiện truy vấn để xóa sản phẩm
    $sql = "DELETE FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result) {
        header("Location: manage_products.php");
        exit();
    } else {
        $error = "Có lỗi khi xóa sản phẩm.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gấu Bông Shop</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Gấu Bông Shop</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Trang Chủ <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sản Phẩm</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Giỏ Hàng</a>
                    </li> -->
                    <li class="nav-item">
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <a class="nav-link" href="logout.php">Đăng Xuất</a>
                    <?php else : ?>
                        <a class="nav-link" href="#">Đăng Nhập</a>
                    <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <a href="cart.php" class="nav-link">Xem Giỏ Hàng</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="container mt-4">
        <h2>Xác Nhận Xóa Sản Phẩm</h2>

        <!-- Form xác nhận xóa sản phẩm -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $product_id; ?>">
            <p>Bạn có chắc chắn muốn xóa sản phẩm này?</p>
            <button type="submit" class="btn btn-danger">Xóa</button>
            <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </section>

    <?php include('footer.php'); ?>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Thêm các thư viện JavaScript cần thiết -->
</body>

</html>
