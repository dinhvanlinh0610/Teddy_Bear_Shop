<?php
// Kết nối CSDL
include_once('connection/connection.php');

// Bắt đầu phiên làm việc
session_start();

// Kiểm tra xem có tham số product_id truyền vào không
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Truy vấn dữ liệu sản phẩm từ CSDL
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    // Kiểm tra xem sản phẩm có tồn tại không
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Thêm sản phẩm vào giỏ hàng khi người dùng ấn nút "Thêm vào giỏ hàng"
        if (isset($_POST['add_to_cart'])) {
            $product_id = $_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

            // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                // Nếu chưa tồn tại, thêm sản phẩm vào giỏ hàng
                $_SESSION['cart'][$product_id] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                ];
            }

            // Chuyển hướng về trang giỏ hàng sau khi thêm vào giỏ hàng
            header("Location: cart.php");
            exit();
        }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Gấu Bông Shop</title>
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
    <h2><?php echo $product['name']; ?></h2>
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $product['image']; ?>" class="img-fluid" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="col-md-6">
                <p><strong>Mô tả:</strong> <?php echo $product['description']; ?></p>
                <p><strong>Giá:</strong> $<?php echo $product['price']; ?></p>
                <form method="post" action="product_detail.php?product_id=<?php echo $product_id; ?>">
            <input type="hidden" name="add_to_cart" value="1">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <label for="quantity">Số lượng:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1">
            <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
        </form>
    </section>

    <footer class="mt-5 text-center">
        <p>&copy; 2024 Gấu Bông Shop</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
    } else {
        // Nếu không tìm thấy sản phẩm, có thể chuyển hướng hoặc hiển thị thông báo
        echo "Sản phẩm không tồn tại.";
    }
} else {
    // Nếu không có tham số product_id, có thể chuyển hướng hoặc hiển thị thông báo
    echo "Không có sản phẩm được chọn.";
}
?>
