<?php
// Kết nối CSDL
include_once('connection/connection.php');

// Bắt đầu phiên làm việc
session_start();

// Thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        // Nếu chưa tồn tại, thêm sản phẩm vào giỏ hàng
        $sql = "SELECT * FROM products WHERE id = $product_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ];
        }
    }
}

// Cập nhật số lượng sản phẩm trong giỏ hàng
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            // Nếu số lượng là 0, xóa sản phẩm khỏi giỏ hàng
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

// Xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
}

// Xóa toàn bộ giỏ hàng
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
}

// Tính tổng giá trị đơn hàng
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>
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
        <h2>Giỏ Hàng</h2>
        <div class="row">
            <div class="col-md-8">
                <?php if (!empty($_SESSION['cart'])) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sản Phẩm</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Số Lượng</th>
                                <th scope="col">Thành Tiền</th>
                                <th scope="col">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $item) : ?>
                                <tr>
                                    <td><?php echo $item['name']; ?></td>
                                    <td>$<?php echo $item['price']; ?></td>
                                    <td>
                                        <form method="post" action="cart.php">
                                            <input type="hidden" name="update_cart" value="1">
                                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                            <input type="number" name="quantity[<?php echo $item['id']; ?>]"
                                                value="<?php echo $item['quantity']; ?>" min="1">
                                            <button type="submit" class="btn btn-link btn-sm">Cập Nhật</button>
                                        </form>
                                    </td>
                                    <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                                    <td><a href="cart.php?remove=<?php echo $item['id']; ?>"
                                            class="btn btn-danger btn-sm">Xóa</a></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Tổng Tiền:</strong></td>
                                <td colspan="2">$<?php echo $total_price; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="cart.php?clear=1" class="btn btn-danger">Xóa Giỏ Hàng</a>
                <?php else : ?>
                    <p>Giỏ hàng trống.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="mt-5 text-center">
        <p>&copy; 2024 Gấu Bông Shop</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
