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
    <!-- Thêm các thẻ meta, title, CSS, và các thư viện cần thiết -->
</head>

<body>

    <header>
        <!-- Thêm phần header -->
    </header>

    <section class="container mt-4">
        <h2>Xác Nhận Xóa Sản Phẩm</h2>

        <!-- Form xác nhận xóa sản phẩm -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $product_id; ?>">
            <p>Bạn có chắc chắn muốn xóa sản phẩm này?</p>
            <button type="submit" class="btn btn-danger">Xóa</button>
        </form>
    </section>

    <footer class="mt-5 text-center">
        <!-- Thêm phần footer -->
    </footer>

    <!-- Thêm các thư viện JavaScript cần thiết -->
</body>

</html>
