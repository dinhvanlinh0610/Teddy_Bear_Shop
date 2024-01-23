<?php
// Kết nối CSDL
include_once('connection/connection.php');
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
            <a class="navbar-brand" href="#">Gấu Bông Shop</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="#">Giỏ Hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Đăng Nhập</a>
                    </li>
                    <li class="nav-item">
                        <a href="cart.php" class="nav-link">Xem Giỏ Hàng</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="container mt-4">
        <!-- Thêm ô nhập liệu và nút tìm kiếm -->
        <div class="search-container">
            <form method="get" action="">
                <input type="text" placeholder="Tìm kiếm sản phẩm" name="search">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <h2>Danh Sách Sản Phẩm</h2>

        <div id="productCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $products = $result->fetch_all(MYSQLI_ASSOC);
                    $productsChunked = array_chunk($products, 3);

                    // Tạo indicators
                    for ($i = 0; $i < count($productsChunked); $i++) {
                ?>
                        <li data-target="#productCarousel" data-slide-to="<?php echo $i; ?>"
                            <?php echo $i === 0 ? 'class="active"' : ''; ?>></li>
                <?php
                    }
                }
                ?>
            </ol>
            <div class="carousel-inner">
                <?php
                // Xử lý tìm kiếm
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $searchTerm = $_GET['search'];
                    $sql = "SELECT * FROM products WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $products = $result->fetch_all(MYSQLI_ASSOC);
                        $productsChunked = array_chunk($products, 3);

                        $firstItem = true;
                        foreach ($productsChunked as $chunk) {
                            $activeClass = $firstItem ? 'active' : '';
                ?>
                            <div class="carousel-item <?php echo $activeClass; ?>">
                                <div class="row">
                                    <?php
                                    foreach ($chunk as $row) {
                                    ?>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <a href="product_detail.php?product_id=<?php echo $row['id']; ?>">
                                                    <img src="<?php echo $row['image']; ?>" class="card-img-top"
                                                        alt="<?php echo $row['name']; ?>">
                                                </a>
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                                    <p class="card-text"><?php echo $row['description']; ?></p>
                                                    <p class="card-text">$<?php echo $row['price']; ?></p>
                                                    <form method="post" action="cart.php">
                                                        <input type="hidden" name="add_to_cart" value="1">
                                                        <input type="hidden" name="product_id"
                                                            value="<?php echo $row['id']; ?>">
                                                        <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                <?php
                            $firstItem = false;
                        }
                    } else {
                        echo "<p>Không tìm thấy sản phẩm nào.</p>";
                    }
                } else {
                    // Hiển thị tất cả sản phẩm khi không có yêu cầu tìm kiếm
                    $firstItem = true;
                    foreach ($productsChunked as $chunk) {
                        $activeClass = $firstItem ? 'active' : '';
                ?>
                        <div class="carousel-item <?php echo $activeClass; ?>">
                            <div class="row">
                                <?php
                                foreach ($chunk as $row) {
                                ?>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <a href="product_detail.php?product_id=<?php echo $row['id']; ?>">
                                                <img src="<?php echo $row['image']; ?>" class="card-img-top"
                                                    alt="<?php echo $row['name']; ?>">
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                                <p class="card-text"><?php echo $row['description']; ?></p>
                                                <p class="card-text">$<?php echo $row['price']; ?></p>
                                                <form method="post" action="cart.php">
                                                    <input type="hidden" name="add_to_cart" value="1">
                                                    <input type="hidden" name="product_id"
                                                        value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                <?php
                        $firstItem = false;
                    }
                }
                ?>
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
