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

$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý dữ liệu khi form được submit
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $size = $_POST['size'];
    $image = $_POST['image']; // Đây là một URL, bạn có thể xử lý tải lên hình ảnh nếu cần

    // Thực hiện truy vấn để cập nhật thông tin sản phẩm
    $sql = "UPDATE products SET name='$name', description='$description', price=$price, type='$type', size='$size', image='$image' WHERE id=$product_id";
    $result = $conn->query($sql);

    if ($result) {
        header("Location: manage_products.php");
        exit();
    } else {
        $error = "Có lỗi khi cập nhật sản phẩm.";
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
        <h2>Sửa Sản Phẩm</h2>

         <!-- Form sửa sản phẩm -->
         <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $product_id; ?>">
            <div class="form-group">
                <label for="name">Tên Sản Phẩm:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Mô Tả:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $product['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Giá:</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Loại:</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo $product['type']; ?>" required>
            </div>
            <div class="form-group">
                <label for="size">Kích Thước:</label>
                <input type="text" class="form-control" id="size" name="size" value="<?php echo $product['size']; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">URL Ảnh:</label>
                <input type="text" class="form-control" id="image" name="image" value="<?php echo $product['image']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </section>

    <footer class="mt-5 text-center">
        <!-- Thêm phần footer -->
    </footer>

    <!-- Thêm các thư viện JavaScript cần thiết -->
</body>

</html>
