<?php
// Kết nối CSDL
include_once('connection/connection.php');

// Bắt đầu phiên làm việc
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng của user hay chưa
    $sqlCheck = "SELECT * FROM carts WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
        // Nếu sản phẩm đã tồn tại, cập nhật số lượng
        $sqlUpdate = "UPDATE carts SET quantity = quantity + $quantity WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
        $conn->query($sqlUpdate);
    } else {
        // Nếu sản phẩm chưa tồn tại, thêm mới vào giỏ hàng
        $sqlInsert = "INSERT INTO carts (user_id, product_id, quantity) VALUES ({$_SESSION['user_id']}, $product_id, $quantity)";
        $conn->query($sqlInsert);
    }

    header("Location: cart.php");
    exit();
}

// Cập nhật số lượng sản phẩm trong giỏ hàng
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $quantity = intval($quantity);

        if ($quantity > 0) {
            // Cập nhật số lượng sản phẩm
            $sqlUpdate = "UPDATE carts SET quantity = $quantity WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
            $conn->query($sqlUpdate);
        } else {
            // Nếu số lượng là 0, xóa sản phẩm khỏi giỏ hàng
            $sqlDelete = "DELETE FROM carts WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
            $conn->query($sqlDelete);
        }
    }
}

// Xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    $sqlDelete = "DELETE FROM carts WHERE user_id = {$_SESSION['user_id']} AND product_id = $product_id";
    $conn->query($sqlDelete);
}

// Xóa toàn bộ giỏ hàng
if (isset($_GET['clear'])) {
    $sqlClear = "DELETE FROM carts WHERE user_id = {$_SESSION['user_id']}";
    $conn->query($sqlClear);
}

// Lấy danh sách sản phẩm trong giỏ hàng của user
$sqlCart = "SELECT products.*, carts.quantity FROM carts INNER JOIN products ON carts.product_id = products.id WHERE carts.user_id = {$_SESSION['user_id']}";
$resultCart = $conn->query($sqlCart);

// Tính tổng giá trị đơn hàng
$total_price = 0;
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
            <a class="navbar-brand" href="index.php">Gấu Bông Shop</a>
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
                <?php if ($resultCart->num_rows > 0) : ?>
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
                            <?php while ($item = $resultCart->fetch_assoc()) : ?>
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
                                <?php $total_price += $item['price'] * $item['quantity']; ?>
                            <?php endwhile; ?>
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